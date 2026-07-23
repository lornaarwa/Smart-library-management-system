import React from 'react';
import { BookOpen, ShieldCheck, Phone, Mail, MapPin } from 'lucide-react';

export default function Footer() {
    return (
        <footer className="bg-slate-950 border-t border-slate-900 mt-20 text-slate-400 text-sm">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    
                    {/* Brand Info */}
                    <div className="space-y-4">
                        <div className="flex items-center gap-3">
                            <div className="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center">
                                <BookOpen className="w-5 h-5 text-white" />
                            </div>
                            <span className="text-lg font-bold text-white">SmartLib System</span>
                        </div>
                        <p className="text-xs leading-relaxed text-slate-400">
                            Empowering academic and public libraries with automated circulation control, instant OPAC search, AI book recommendations, and M-Pesa Daraja fine payments.
                        </p>
                    </div>

                    {/* Quick Links */}
                    <div>
                        <h4 className="text-white font-semibold mb-3">Quick Portals</h4>
                        <ul className="space-y-2 text-xs">
                            <li><a href="/" className="hover:text-indigo-400 transition-colors">Online Public Access Catalog (OPAC)</a></li>
                            <li><a href="/member" className="hover:text-indigo-400 transition-colors">Member Dashboard & Loans</a></li>
                            <li><a href="/librarian" className="hover:text-indigo-400 transition-colors">Librarian Circulation Desk</a></li>
                            <li><a href="/admin" className="hover:text-indigo-400 transition-colors">System Admin Console</a></li>
                        </ul>
                    </div>

                    {/* Infrastructure & Security */}
                    <div>
                        <h4 className="text-white font-semibold mb-3">Architecture & Hosting</h4>
                        <ul className="space-y-2 text-xs">
                            <li className="flex items-center gap-2"><ShieldCheck className="w-4 h-4 text-emerald-400" /> PostgreSQL & Eloquent ORM</li>
                            <li>JWT Token & Rate Limited API Gateway</li>
                            <li>M-Pesa Daraja STK Push Provider</li>
                            <li>Offline / VPS Deployment Ready</li>
                        </ul>
                    </div>

                    {/* Contact & Hours */}
                    <div className="space-y-2 text-xs">
                        <h4 className="text-white font-semibold mb-3">Library Helpdesk</h4>
                        <p className="flex items-center gap-2"><MapPin className="w-4 h-4 text-indigo-400" /> Main Campus Library, Block B</p>
                        <p className="flex items-center gap-2"><Phone className="w-4 h-4 text-indigo-400" /> +254 700 000 000 (M-Pesa Support)</p>
                        <p className="flex items-center gap-2"><Mail className="w-4 h-4 text-indigo-400" /> support@smartlib.org</p>
                    </div>

                </div>

                <div className="border-t border-slate-900 mt-10 pt-6 text-center text-xs text-slate-500">
                    &copy; {new Date().getFullYear()} Smart Library Management System. All rights reserved.
                </div>
            </div>
        </footer>
    );
}
