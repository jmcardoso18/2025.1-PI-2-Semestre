const langToggle = document.getElementById('langToggle');
let currentLang = 'pt';

const translations = {
    en: {
        "nav.about": "About Us",
        "nav.products": "Products",
        "nav.clients": "Clients",
        "nav.contact": "Contact",
        "about.title": "About Us",
        "about.desc": "Over 10 years in the IT market, LED lighting projects, and now also in electrical cables, tubes, and precast materials.",
        "products.title": "Products",
        "products.desc": "IT, Lighting, Cables, Tubes, Precast",
        "clients.title": "Our Clients",
        "contact.title": "Contact",
    },
    pt: {
        "nav.about": "Sobre nós",
        "nav.products": "Produtos",
        "nav.clients": "Clientes",
        "nav.contact": "Contato",
        "about.title": "Sobre nós",
        "about.desc": "Mais de 10 anos no mercado de TI, projetos de iluminação em LED e agora também em cabos elétricos, tubos e pré-moldados.",
        "products.title": "Produtos",
        "products.desc": "TI, Iluminação, Cabos, Tubos, Pré-moldados",
        "clients.title": "Nossos Clientes",
        "contact.title": "Contato",

    }
};

langToggle.addEventListener('click', () => {
    currentLang = currentLang === 'pt' ? 'en' : 'pt';
    langToggle.textContent = currentLang === 'pt' ? 'EN' : 'PT';
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        const text = translations[currentLang]?.[key];
        if (text) el.textContent = text;
    });
});




