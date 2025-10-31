import type { User } from '@/interfaces/user.interface';

export function setToken(token: string) {
  localStorage.setItem('authToken', token);
}

export function getToken() {
  return localStorage.getItem('authToken');
}

export function removeToken() {
  localStorage.removeItem('authToken');
}

export function setUser(user: User) {
  localStorage.setItem('userId', user.id);
  localStorage.setItem('userName', user.name);
  localStorage.setItem('userEmail', user.email);
  localStorage.setItem('userRole', user.role);
  localStorage.setItem('userDepartment', user.department);
}

export function getUser(): User {
  const userId = localStorage.getItem('userId');
  const userName = localStorage.getItem('userName');
  const userEmail = localStorage.getItem('userEmail');
  const userRole = localStorage.getItem('userRole');
  const userDepartment = localStorage.getItem('userDepartment');

  const user: User = {
    id: userId!,
    name: userName!,
    email: userEmail!,
    role: userRole!,
    department: userDepartment!,
  };
  return user;
}
