
import axios, { InternalAxiosRequestConfig } from 'axios';
import { jwtDecode } from 'jwt-decode';
import { AdminJwtPayloadType } from '../types/AdminTypes';



export function authByToken() {
  const accessToken = localStorage.getItem('accessToken');
  if (!accessToken || accessToken === 'undefined') {
    window.location.href = '/admin'
    return null;
  } else return jwtDecode<AdminJwtPayloadType>(accessToken);
}

export const fetchAuthentication = axios.create();

fetchAuthentication.interceptors.request.use(
  (config) => {
    const accessToken = localStorage.getItem('accessToken');

    if (!accessToken) {
      return config;
    }

    return {
      ...config,
      headers: {
        ...config.headers,
        Authorization: `Bearer ${accessToken}`
      }
    } as InternalAxiosRequestConfig<string>;
  },
  (error) => Promise.reject(error)
);


fetchAuthentication.interceptors.response.use(
  (response) => response,
  (error) => {

    if (error.response.status !== 403) {
      return Promise.reject(error);
    }

    const originalRequest = error.config;

    if (originalRequest.isRetry) {
      return Promise.reject(error);
    }

    originalRequest.isRetry = true;

    return axios
      .get("/get-token", {
        withCredentials: true,
      })
      .then((res) => {

        const accessToken = res.data.accessToken;
        console.log(accessToken);
        if (!accessToken) {
          localStorage.removeItem('accessToken');
          window.location.href = '/admin';
          return;
        }

        localStorage.removeItem('accessToken');
        localStorage.setItem('accessToken', accessToken);
      })
      .then(() => fetchAuthentication(originalRequest))
      .catch(err => {
        console.error(err);
        localStorage.removeItem('accessToken');
        window.location.href = "/admin";
      })
  }
);