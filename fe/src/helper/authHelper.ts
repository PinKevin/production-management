import type { User } from '@/interfaces/user.interface';

const TOKEN_KEY = 'authToken';
const USER_ID_KEY = 'userId';
const USER_NAME_KEY = 'userName';
const USER_EMAIL_KEY = 'userEmail';
const USER_ROLE_KEY = 'userRole';
const USER_DEPARTMENT_KEY = 'userDepartment';

export function setToken(token: string) {
  localStorage.setItem(TOKEN_KEY, token);
}

export function getToken() {
  return localStorage.getItem(TOKEN_KEY);
}

export function removeToken() {
  localStorage.removeItem(TOKEN_KEY);
}

export function setUser(user: User) {
  localStorage.setItem(USER_ID_KEY, user.id);
  localStorage.setItem(USER_NAME_KEY, user.name);
  localStorage.setItem(USER_EMAIL_KEY, user.email);
  localStorage.setItem(USER_ROLE_KEY, user.role);
  localStorage.setItem(USER_DEPARTMENT_KEY, user.department);
}

export function getUser(): User | null {
  const userId = localStorage.getItem(USER_ID_KEY);
  const userName = localStorage.getItem(USER_NAME_KEY);
  const userEmail = localStorage.getItem(USER_EMAIL_KEY);
  const userRole = localStorage.getItem(USER_ROLE_KEY);
  const userDepartment = localStorage.getItem(USER_DEPARTMENT_KEY);

  if (!userId || !userName || !userRole || !userDepartment) {
    return null;
  }

  const user: User = {
    id: userId!,
    name: userName!,
    email: userEmail!,
    role: userRole!,
    department: userDepartment!,
  };
  return user;
}

export function clearAuthData() {
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(USER_ID_KEY);
  localStorage.removeItem(USER_NAME_KEY);
  localStorage.removeItem(USER_EMAIL_KEY);
  localStorage.removeItem(USER_ROLE_KEY);
  localStorage.removeItem(USER_DEPARTMENT_KEY);
}
