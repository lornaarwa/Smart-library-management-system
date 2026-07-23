import React, { createContext, useContext, useState, useEffect } from 'react';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(() => {
        const saved = localStorage.getItem('smartlib_user');
        return saved ? JSON.parse(saved) : {
            id: 1,
            name: 'Demo Member',
            email: 'member@library.org',
            role: 'member', // 'member', 'librarian', 'admin'
            member: {
                id: 1,
                member_number: 'MEM-8890',
                membership_tier: 'student',
                borrow_limit: 5,
                is_banned: false,
            }
        };
    });

    const [token, setToken] = useState(() => localStorage.getItem('smartlib_token') || 'demo_jwt_token_sample');

    useEffect(() => {
        if (user) {
            localStorage.setItem('smartlib_user', JSON.stringify(user));
        } else {
            localStorage.removeItem('smartlib_user');
        }
    }, [user]);

    useEffect(() => {
        if (token) {
            localStorage.setItem('smartlib_token', token);
        } else {
            localStorage.removeItem('smartlib_token');
        }
    }, [token]);

    const loginAsRole = (role) => {
        let newUser = {};
        if (role === 'admin') {
            newUser = {
                id: 99,
                name: 'System Admin',
                email: 'admin@library.org',
                role: 'admin',
            };
        } else if (role === 'librarian') {
            newUser = {
                id: 50,
                name: 'Head Librarian',
                email: 'librarian@library.org',
                role: 'librarian',
                librarian: { id: 1, employee_id: 'LIB-1002', department: 'Circulation' }
            };
        } else {
            newUser = {
                id: 1,
                name: 'Alex Johnson',
                email: 'alex@student.edu',
                role: 'member',
                member: {
                    id: 1,
                    member_number: 'MEM-2026',
                    membership_tier: 'student',
                    borrow_limit: 5,
                    is_banned: false,
                }
            };
        }
        setUser(newUser);
        setToken(`token_${role}_${Date.now()}`);
    };

    const logout = () => {
        setUser(null);
        setToken(null);
    };

    return (
        <AuthContext.Provider value={{ user, setUser, token, setToken, loginAsRole, logout }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);
