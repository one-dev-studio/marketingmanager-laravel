export function WidgetLoading() {
    return (
        <div className="py-8 text-center">
            <div
                className="mx-auto h-8 w-8 animate-spin rounded-full border-b-2 border-primary-600"
                role="status"
                aria-label="Loading"
            />
        </div>
    );
}
