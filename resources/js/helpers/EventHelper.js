export function dispatchCustomEvent(event, data) {
    window.dispatchEvent(new CustomEvent(event, { detail: data }));
}
