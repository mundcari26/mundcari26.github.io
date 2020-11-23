class Banner extends HTMLElement {
    constructor() {
        super();
    }
    connectedCallback() {
        this.innerHTML = `
        <nav class="nav-wrapper blue lighten-2 ">
            <div class="container">
                <a class="brand-logo center">Rompecabezas</a>
            </div>
        </nav>
    
        `
    }
}
window.customElements.define('ban-ner', Banner);