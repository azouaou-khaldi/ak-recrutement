/**
 * Indicateur de défilement horizontal des tableaux (composant <x-tableau-defilant>).
 *
 * Sur mobile, un tableau trop large défile dans sa carte. Sans indication,
 * l'utilisateur ne devine pas que des colonnes sont cachées à droite :
 * - un message « Faites glisser le tableau… » s'affiche s'il y a du contenu caché ;
 * - une ombre sur le bord droit reste visible tant que la fin n'est pas atteinte.
 * Sur grand écran, le tableau tient en entier : rien ne s'affiche.
 */
export function initTableauxDefilants() {
    document.querySelectorAll('[data-tableau-defilant]').forEach((bloc) => {
        const zone = bloc.querySelector('[data-zone-defilement]');
        const indice = bloc.querySelector('[data-indice-defilement]');
        const ombre = bloc.querySelector('[data-ombre-defilement]');

        const mettreAJour = () => {
            const defilable = zone.scrollWidth > zone.clientWidth + 1;
            const auBout = zone.scrollLeft + zone.clientWidth >= zone.scrollWidth - 1;

            indice.hidden = !defilable;
            ombre.hidden = !defilable || auBout;
        };

        zone.addEventListener('scroll', mettreAJour, { passive: true });
        // Recalcul quand la largeur change (rotation du téléphone, redimensionnement)
        new ResizeObserver(mettreAJour).observe(zone);
        mettreAJour();
    });
}
