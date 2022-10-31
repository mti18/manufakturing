import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

// const axios = Axios.create({
//   baseURL: import.meta.env.VITE_URL + "/api/v1",
//   headers: {
//     "X-Requested-With": "XMLHttpRequest",
//     Accept: "application/json",
//     Authorization: "Bearer " + localStorage.getItem("auth-token"),
//   },
// });

axios.interceptors.request.use(
  (config) => {
    config.baseURL = import.meta.env.VITE_URL + "/api/v1";
    config.headers["X-Requested-With"] = "XMLHttpRequest";
    config.headers["Accept"] = "application/json";
    config.headers["Authorization"] = "Bearer " + localStorage.getItem("auth-token");
    return config;
  },
  (error) => {
    if (route().current() !== 'login' && route().current() !== 'register') {
      Inertia.visit('/login');
    }
    return Promise.reject(error);
  }
);

export default axios;
