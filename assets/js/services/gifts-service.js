import axios from 'axios';

/**
 * @returns {Promise}
 */
export function fetchGifts(page) {
  if (!page) {
    page = 1;
  }

  return axios.get(`/api/gifts?page=${page}&order[createdAt]=desc`);
}

export function fetchGift(peopleId) {
  return axios.get(peopleId);
}
