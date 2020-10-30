/**
 * @returns {Promise}
 */
export function fetchPeoples() {
  return new Promise((resolve) => {
    resolve({
      data: {
        'hydra:member': window.peoples,
      },
    });
  });
}
