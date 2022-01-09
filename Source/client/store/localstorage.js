import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({
  page: 'home',
})

export const mutations = {
  updatePage(state, page) {
    state.page = page;
  },
}
