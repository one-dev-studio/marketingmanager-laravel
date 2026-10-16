/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}

declare module 'canvas-confetti' {
    interface ConfettiOptions {
        particleCount?: number;
        startVelocity?: number;
        spread?: number;
        ticks?: number;
        zIndex?: number;
        origin?: { x?: number; y?: number };
    }

    function confetti(options?: ConfettiOptions): void;

    export default confetti;
}
