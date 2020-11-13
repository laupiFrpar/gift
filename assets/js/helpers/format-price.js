/**
 * Formats a price buy adding a dot and normalizing decimals
 *
 * @param {number} price
 * @returns {string}
 */
export default (price) => (
  `${price.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €`
);
