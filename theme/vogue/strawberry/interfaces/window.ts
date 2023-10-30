export type GlobalWindowObject = Window & typeof globalThis & {
    deployment: {
        name: 'production' | 'default'
    }
}