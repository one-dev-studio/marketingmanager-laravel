import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import OnboardingWizard from './components/OnboardingWizard.vue';
import TaskKanban from './components/TaskKanban.vue';
import LandingPageBuilder from './components/LandingPageBuilder.vue';
import SurveyBuilder from './components/SurveyBuilder.vue';
import EmailTemplateBuilder from './components/EmailTemplateBuilder.vue';
import ReportBuilder from './components/ReportBuilder.vue';
import CommandPopover from './components/CommandPopover.vue';

window.Alpine = Alpine;
Alpine.start();

function propsFrom(el) {
    if (!el.dataset.props) {
        return {};
    }

    try {
        return JSON.parse(el.dataset.props);
    } catch {
        return {};
    }
}

function mount(selector, component, extraProps = {}) {
    const el = document.querySelector(selector);
    if (!el) {
        return;
    }
    createApp(component, { ...propsFrom(el), ...extraProps }).mount(el);
}

mount('#onboarding-app', OnboardingWizard);

const kanban = document.getElementById('task-kanban-app');
if (kanban) {
    createApp({})
        .component('task-kanban', TaskKanban)
        .mount(kanban);
}

const lp = document.getElementById('lp-builder');
if (lp) {
    createApp({}).component('landing-page-builder', LandingPageBuilder).mount(lp);
}

const survey = document.getElementById('survey-builder');
if (survey) {
    createApp({}).component('survey-builder', SurveyBuilder).mount(survey);
}

const email = document.getElementById('email-builder-app');
if (email) {
    createApp({}).component('email-template-builder', EmailTemplateBuilder).mount(email);
}

const report = document.getElementById('report-builder-app');
if (report) {
    createApp({}).component('report-builder', ReportBuilder).mount(report);
}

const command = document.getElementById('command-popover-mount');
if (command) {
    createApp({}).component('command-popover', CommandPopover).mount(command);
}
