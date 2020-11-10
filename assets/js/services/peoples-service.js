import axios from 'axios';

/**
 * @returns {Promise}
 */
export function fetchPeoples(page) {
  if (!page) {
    page = 1;
  }

  return axios.get(`/api/peoples?page=${page}&order[createdAt]=desc`);
}

export function fetchPeople(peopleId) {
  return axios.get(peopleId);
}
