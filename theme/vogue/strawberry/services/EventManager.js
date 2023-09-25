import { app } from "../app";
app.service('EventManager', () => {
    class EventManager {
        constructor() {
            this.events = {};
        }
        register(name) {
            const event = new Event;
            event.setName(name);
            if (!this.events.hasOwnProperty(name)) {
                this.events[name] = event;
            }
        }
        subscribe(name, listener) {
            if (!this.events.hasOwnProperty(name)) {
                this.register(name);
            }
            this.events[name].addListener(listener);
        }
        dispatch(name) {
            if (!this.events.hasOwnProperty(name))
                return;
            this.events[name].getListeners().forEach(async (callback) => {
                await Promise.resolve(callback());
            });
        }
    }
    class Event {
        constructor() {
            this.name = '',
                this.listeners = [];
        }
        setName(name) {
            this.name = name;
        }
        addListener(listener) {
            this.listeners.push(listener);
        }
        getListeners() {
            return this.listeners;
        }
    }
    const eventManager = new EventManager;
    return eventManager;
});
