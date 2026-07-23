import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { BookOpen, KeyRound, Mail, Shield, User, UserCheck } from 'lucide-react';

export default function Login() {
    const { loginAsRole } = useAuth();
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleDemoLogin = (role) => {
        loginAsRole(role);
        if (role === 'admin') navigate('/admin');
        else if (role === 'librarian') navigate('/librarian');
        else navigate('/member');
    };

    return (
        <div className="max-w-md mx-auto px-4 py-16">
            <div className="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
                
                <div className="text-center space-y-2">
                    <div className="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center mx-auto shadow-lg shadow-indigo-600/30">
                        <BookOpen className="w-6 h-6 text-white" />
                    </div>
                    <h1 className="text-2xl font-extrabold text-white">SmartLib Sign In</h1>
                    <p className="text-xs text-slate-400">Access Member, Librarian, or Admin Portals</p>
                </div>

                <div className="space-y-4">
                    <div>
                        <label className="block text-xs font-semibold text-slate-300 mb-1">Email Address</label>
                        <div className="relative">
                            <Mail className="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5" />
                            <input
                                type="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                placeholder="name@library.org"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors"
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-xs font-semibold text-slate-300 mb-1">Password</label>
                        <div className="relative">
                            <KeyRound className="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5" />
                            <input
                                type="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                placeholder="••••••••"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors"
                            />
                        </div>
                    </div>

                    <button
                        type="button"
                        onClick={() => handleDemoLogin('member')}
                        className="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition-all"
                    >
                        Sign In as Member
                    </button>
                </div>

                <div className="relative py-2">
                    <div className="absolute inset-0 flex items-center"><div className="w-full border-t border-slate-800"></div></div>
                    <div className="relative flex justify-center text-[10px] uppercase font-bold tracking-wider text-slate-500">
                        <span className="bg-slate-900 px-3">Instant Demo Role Portals</span>
                    </div>
                </div>

                <div className="grid grid-cols-3 gap-2">
                    <button
                        onClick={() => handleDemoLogin('member')}
                        className="p-3 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 text-center transition-colors group"
                    >
                        <User className="w-4 h-4 text-indigo-400 mx-auto mb-1 group-hover:scale-110 transition-transform" />
                        <span className="text-[10px] font-bold text-slate-300 block">Member</span>
                    </button>

                    <button
                        onClick={() => handleDemoLogin('librarian')}
                        className="p-3 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 text-center transition-colors group"
                    >
                        <UserCheck className="w-4 h-4 text-amber-400 mx-auto mb-1 group-hover:scale-110 transition-transform" />
                        <span className="text-[10px] font-bold text-slate-300 block">Librarian</span>
                    </button>

                    <button
                        onClick={() => handleDemoLogin('admin')}
                        className="p-3 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 text-center transition-colors group"
                    >
                        <Shield className="w-4 h-4 text-rose-400 mx-auto mb-1 group-hover:scale-110 transition-transform" />
                        <span className="text-[10px] font-bold text-slate-300 block">Admin</span>
                    </button>
                </div>

            </div>
        </div>
    );
}
