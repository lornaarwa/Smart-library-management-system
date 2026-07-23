import React, { useState } from 'react';
import { Shield, Users, Database, Sparkles, Activity, Ban, CheckCircle2, Server, Key } from 'lucide-react';

export default function AdminDashboard() {
    const [memberBanNotice, setMemberBanNotice] = useState('');
    const [targetMember, setTargetMember] = useState('MEM-2026');

    const handleBanToggle = (e) => {
        e.preventDefault();
        setMemberBanNotice(`Member ${targetMember} banned/suspended status updated. CheckBannedStatus middleware will enforce ban.`);
        setTimeout(() => setMemberBanNotice(''), 4000);
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            {/* Admin Header */}
            <div className="bg-gradient-to-r from-rose-950/60 via-slate-900 to-slate-950 border border-rose-500/30 rounded-3xl p-6 md:p-8 flex items-center justify-between">
                <div>
                    <span className="text-xs font-bold text-rose-400 uppercase tracking-wider block mb-1">
                        System Control Unit
                    </span>
                    <h1 className="text-2xl md:text-3xl font-extrabold text-white">
                        Administrator Dashboard
                    </h1>
                    <p className="text-xs text-slate-400 mt-1">
                        Database ORM status, AI token usage metrics, rate limiters, & member ban controls.
                    </p>
                </div>
            </div>

            {memberBanNotice && (
                <div className="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200 flex items-center gap-3 animate-in fade-in">
                    <CheckCircle2 className="w-5 h-5 text-rose-400 shrink-0" />
                    <span className="text-sm font-semibold">{memberBanNotice}</span>
                </div>
            )}

            {/* Core Metrics Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div className="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-2">
                    <div className="flex justify-between items-center text-slate-400">
                        <span className="text-xs font-semibold">Total System Users</span>
                        <Users className="w-5 h-5 text-indigo-400" />
                    </div>
                    <span className="text-2xl font-extrabold text-white block">1,248</span>
                    <span className="text-[10px] text-emerald-400 font-semibold">+12% from last month</span>
                </div>

                <div className="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-2">
                    <div className="flex justify-between items-center text-slate-400">
                        <span className="text-xs font-semibold">PostgreSQL DB Status</span>
                        <Database className="w-5 h-5 text-emerald-400" />
                    </div>
                    <span className="text-2xl font-extrabold text-emerald-400 block">Healthy</span>
                    <span className="text-[10px] text-slate-500 font-semibold">Eloquent Contracts Active</span>
                </div>

                <div className="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-2">
                    <div className="flex justify-between items-center text-slate-400">
                        <span className="text-xs font-semibold">AI Tokens Consumed</span>
                        <Sparkles className="w-5 h-5 text-cyan-400" />
                    </div>
                    <span className="text-2xl font-extrabold text-cyan-300 block">45,820</span>
                    <span className="text-[10px] text-slate-400 font-semibold">Cost: ~$0.068 USD</span>
                </div>

                <div className="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-2">
                    <div className="flex justify-between items-center text-slate-400">
                        <span className="text-xs font-semibold">Active Gateways</span>
                        <Server className="w-5 h-5 text-purple-400" />
                    </div>
                    <span className="text-2xl font-extrabold text-white block">API Proxy v1</span>
                    <span className="text-[10px] text-emerald-400 font-semibold">Rate Limit: 60req/min</span>
                </div>

            </div>

            {/* Member Ban Control */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-5">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                            <Ban className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-bold text-white">Member Suspension & Ban Tool</h2>
                            <p className="text-xs text-slate-400">Enforce CheckBannedStatus middleware restrictions</p>
                        </div>
                    </div>

                    <form onSubmit={handleBanToggle} className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Member Number</label>
                            <input
                                type="text"
                                value={targetMember}
                                onChange={(e) => setTargetMember(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-rose-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Reason for Suspension</label>
                            <input
                                type="text"
                                defaultValue="Unreturned high-value materials and overdue fine defaults."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-rose-500 transition-colors"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            className="w-full py-3.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-sm shadow-lg shadow-rose-600/30 transition-all"
                        >
                            Toggle Member Ban Status
                        </button>
                    </form>
                </div>

                {/* Middleware Audit Status */}
                <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-4">
                    <h2 className="text-base font-bold text-white flex items-center gap-2">
                        <Activity className="w-5 h-5 text-indigo-400" /> System Middleware Stack (14 Middlewares)
                    </h2>

                    <div className="grid grid-cols-2 gap-2 text-xs font-mono">
                        {[
                            'EnsureIsLibrarian', 'EnsureHasAccount', 'ValidateBorrowLimit',
                            'CheckBookAvailability', 'CheckReservationAvailability', 'JwtTokenValidation',
                            'ThrottleRequests', 'IpRateLimiter', 'Cors', 'TrustProxies',
                            'ApiGatewayProxy', 'CheckBannedStatus', 'CheckFineAmount', 'ChatbotCostLimiter'
                        ].map((mw, idx) => (
                            <div key={idx} className="p-2.5 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                                <span className="text-slate-300 truncate">{mw}</span>
                                <span className="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">ACTIVE</span>
                            </div>
                        ))}
                    </div>
                </div>

            </div>
        </div>
    );
}
