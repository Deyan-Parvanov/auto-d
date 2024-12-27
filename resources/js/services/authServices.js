import apiClient from "@/api";

export async function loginUser({ email, password }) {
  try {
    const res = await apiClient.post(`/login`, {
      email,
      password,
    });

    localStorage.setItem('authToken', res.data.token);

    return res.data;
  }
  catch (e) {
    console.error('Ops something went wrong', e);
    return null;
  }
}

export function getToken() {
  return localStorage.getItem('authToken');
}

export async function registerUser({ email, name, password, confirmPassword }) {
  try {
    const res = await apiClient.post(`/register`, {
      email,
      name,
      password,
      confirmPassword,
    });

    return res.data;
  }
  catch (e) {
    console.error('Ops something went wrong', e);
    return null;
  }
}
