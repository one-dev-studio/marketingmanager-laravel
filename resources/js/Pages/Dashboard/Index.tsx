import { colSpanClass } from '@/components/dashboard/colSpanClass';
import { DashboardWidgetBody } from '@/components/dashboard/DashboardWidgetBody';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { DashboardWidgetRecord, DashboardWidgetTypeOption } from '@/types/dashboard';
import axios from 'axios';
import { useCallback, useEffect, useRef, useState } from 'react';
import Sortable from 'sortablejs';
import { route } from 'ziggy-js';

export interface DashboardIndexProps {
    organizationId: string;
    userName: string;
}

const AVAILABLE_WIDGET_TYPES: DashboardWidgetTypeOption[] = [
    { type: 'kpi', name: 'KPIs', icon: '📊' },
    { type: 'activity_feed', name: 'Activity Feed', icon: '📝' },
    { type: 'pending_tasks', name: 'Pending Tasks', icon: '✅' },
    { type: 'calendar_preview', name: 'Calendar Preview', icon: '📅' },
    { type: 'campaign_performance', name: 'Campaign Performance', icon: '📈' },
];

export default function Index({ organizationId, userName }: DashboardIndexProps) {
    const [widgets, setWidgets] = useState<DashboardWidgetRecord[]>([]);
    const [editMode, setEditMode] = useState(false);
    const gridRef = useRef<HTMLDivElement | null>(null);
    const sortableRef = useRef<Sortable | null>(null);

    const loadWidgets = useCallback(async () => {
        try {
            const response = await axios.get<{ data: DashboardWidgetRecord[] }>(
                route('main.dashboard.widgets.index', { organizationId }),
            );
            setWidgets(response.data.data ?? []);
        } catch {
            setWidgets([]);
        }
    }, [organizationId]);

    useEffect(() => {
        void loadWidgets();
    }, [loadWidgets]);

    const destroySortable = useCallback(() => {
        if (sortableRef.current) {
            sortableRef.current.destroy();
            sortableRef.current = null;
        }
    }, []);

    const saveWidgetPositions = useCallback(async () => {
        try {
            const positions = widgets.map((widget) => ({
                id: widget.id,
                position_x: widget.position_x,
                position_y: widget.position_y,
                width: widget.width,
                height: widget.height,
            }));
            await axios.put(route('main.dashboard.widgets.update-positions', { organizationId }), {
                widgets: positions,
            });
        } catch {
            // Positions remain local until next successful save.
        }
    }, [organizationId, widgets]);

    const initSortable = useCallback(() => {
        if (!gridRef.current || sortableRef.current) {
            return;
        }

        sortableRef.current = Sortable.create(gridRef.current, {
            animation: 150,
            handle: '.widget-drag-handle',
            onEnd: (event: Sortable.SortableEvent) => {
                const item = event.item as HTMLElement;
                const widgetId = Number.parseInt(item.dataset.widgetId ?? '', 10);
                if (Number.isNaN(widgetId)) {
                    return;
                }
                setWidgets((current) => {
                    const next = [...current];
                    const fromIndex = next.findIndex((w) => w.id === widgetId);
                    if (fromIndex < 0 || event.newIndex === undefined) {
                        return current;
                    }
                    const [moved] = next.splice(fromIndex, 1);
                    next.splice(event.newIndex, 0, moved);
                    const cols = 12;
                    return next.map((widget, index) => ({
                        ...widget,
                        position_x: index % cols,
                        position_y: Math.floor(index / cols),
                    }));
                });
            },
        });
    }, []);

    const toggleEditMode = () => {
        if (editMode) {
            destroySortable();
            void saveWidgetPositions();
            setEditMode(false);
            return;
        }
        setEditMode(true);
    };

    useEffect(() => {
        if (editMode) {
            initSortable();
            return () => {
                destroySortable();
            };
        }
        destroySortable();
        return undefined;
    }, [editMode, initSortable, destroySortable, widgets.length]);

    const addWidget = async (widgetType: DashboardWidgetTypeOption) => {
        try {
            const response = await axios.post<{ data: DashboardWidgetRecord }>(
                route('main.dashboard.widgets.store', { organizationId }),
                {
                    widget_type: widgetType.type,
                    title: widgetType.name,
                    position_x: widgets.length % 12,
                    position_y: Math.floor(widgets.length / 12),
                    width: widgetType.type === 'kpi' ? 3 : 6,
                    height: 4,
                },
            );
            setWidgets((current) => [...current, response.data.data]);
        } catch {
            window.alert('Failed to add widget. Please try again.');
        }
    };

    const removeWidget = async (widgetId: number) => {
        if (!window.confirm('Are you sure you want to remove this widget?')) {
            return;
        }
        try {
            await axios.delete(
                route('main.dashboard.widgets.destroy', { organizationId, widget: widgetId }),
            );
            setWidgets((current) => current.filter((w) => w.id !== widgetId));
        } catch {
            window.alert('Failed to remove widget. Please try again.');
        }
    };

    return (
        <AppLayout title="Dashboard">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Dashboard</h1>
                        <p className="mt-1 text-sm text-gray-600">Welcome back, {userName}</p>
                    </div>
                    <Button
                        type="button"
                        variant={editMode ? 'default' : 'outline'}
                        onClick={toggleEditMode}
                    >
                        {editMode ? 'Done Editing' : 'Customize Dashboard'}
                    </Button>
                </div>

                {editMode ? (
                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <h2 className="mb-4 text-lg font-semibold text-gray-900">Add Widget</h2>
                        <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                            {AVAILABLE_WIDGET_TYPES.map((widgetType) => (
                                <button
                                    key={widgetType.type}
                                    type="button"
                                    onClick={() => void addWidget(widgetType)}
                                    className="rounded-lg border-2 border-dashed border-gray-300 p-4 transition-colors hover:border-primary-500 hover:bg-primary-50"
                                >
                                    <div className="text-center">
                                        <div className="mb-2 text-2xl">{widgetType.icon}</div>
                                        <div className="text-sm font-medium">{widgetType.name}</div>
                                    </div>
                                </button>
                            ))}
                        </div>
                    </div>
                ) : null}

                {widgets.length > 0 ? (
                    <div
                        ref={gridRef}
                        className={`grid grid-cols-12 gap-4 ${editMode ? 'cursor-move' : ''}`}
                    >
                        {widgets.map((widget) => (
                            <div
                                key={widget.id}
                                data-widget-id={widget.id}
                                className={`${colSpanClass(widget.width)} ${editMode ? 'transition-transform hover:z-10 hover:scale-[1.02]' : ''}`}
                                style={{ gridRow: `span ${widget.height}` }}
                            >
                                <div className="relative h-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                                    <div className="mb-4 flex items-center justify-between border-b border-gray-200 pb-3">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            {widget.title}
                                        </h3>
                                        {editMode ? (
                                            <div className="flex items-center space-x-2">
                                                <button
                                                    type="button"
                                                    onClick={() => void removeWidget(widget.id)}
                                                    className="text-red-600 hover:text-red-800"
                                                    aria-label={`Remove ${widget.title}`}
                                                >
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <span
                                                    className="widget-drag-handle cursor-move text-gray-400 hover:text-gray-600"
                                                    aria-hidden="true"
                                                >
                                                    ≡
                                                </span>
                                            </div>
                                        ) : null}
                                    </div>
                                    <DashboardWidgetBody
                                        organizationId={organizationId}
                                        widgetType={widget.widget_type}
                                    />
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="rounded-lg border border-dashed border-gray-300 py-12 text-center">
                        <h3 className="text-sm font-medium text-gray-900">No widgets</h3>
                        <p className="mt-1 text-sm text-gray-500">
                            Get started by adding a widget to your dashboard.
                        </p>
                        <div className="mt-6">
                            <Button type="button" onClick={() => setEditMode(true)}>
                                Add Widget
                            </Button>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
