/**
 * Menu mobile (bouton burger).
 *
 * Un bouton [data-menu-toggle] ouvre/ferme l'élément dont l'id est indiqué
 * dans son attribut aria-controls. Pour masquer le menu, on ajoute la classe
 * indiquée dans data-menu-hidden-class sur cet élément ("hidden" par défaut).
 * Un fond sombre optionnel [data-menu-overlay="<id>"] ferme le menu au clic.
 */
export function initMenus() {
    document.querySelectorAll('[data-menu-toggle]').forEach((bouton) => {
        const menu = document.getElementById(bouton.getAttribute('aria-controls'));
        if (!menu) {
            return;
        }

        const classeMasquee = menu.dataset.menuHiddenClass || 'hidden';
        const fond = document.querySelector(`[data-menu-overlay="${menu.id}"]`);
        const iconeOuvrir = bouton.querySelector('[data-icone-ouvrir]');
        const iconeFermer = bouton.querySelector('[data-icone-fermer]');

        const basculer = (ouvrir) => {
            menu.classList.toggle(classeMasquee, !ouvrir);
            fond?.classList.toggle('hidden', !ouvrir);
            iconeOuvrir?.classList.toggle('hidden', ouvrir);
            iconeFermer?.classList.toggle('hidden', !ouvrir);

            // Accessibilité : les lecteurs d'écran savent si le menu est ouvert
            bouton.setAttribute('aria-expanded', String(ouvrir));
            bouton.setAttribute('aria-label', ouvrir ? 'Fermer le menu' : 'Ouvrir le menu');
        };

        bouton.addEventListener('click', () => {
            basculer(bouton.getAttribute('aria-expanded') !== 'true');
        });

        fond?.addEventListener('click', () => basculer(false));

        // La touche Échap ferme le menu et redonne le focus au bouton
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && bouton.getAttribute('aria-expanded') === 'true') {
                basculer(false);
                bouton.focus();
            }
        });
    });
}
