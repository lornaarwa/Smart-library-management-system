import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import DarajaPayModal from '../components/DarajaPayModal';
import { BookOpen, Clock, AlertTriangle, CreditCard, Sparkles, CheckCircle2, RotateCw } from 'lucide-react';

const MOCK_MEMBER_LOANS = [
    { id: 101, book_title: 'Clean Code: A Handbook of Agile Software Craftsmanship', barcode: 'BC-9780132350884-001', loan_date: '2026-07-10', due_date: '2026-07-24', status: 'active', renewal_count: 1 },
    { id: 102, book_title: 'The Great Gatsby', barcode: 'BC-9780743273565-002', loan_date: '2026-06-15', due_date: '2026-06-29', status: 'overdue', renewal_count: 0 }
];

const MOCK_FINES = [
    { id: 1, loan_id: 102, amount: 250.00, balance: 250.00, status: 'unpaid', reason: 'Overdue Book (14 days past due)' }
];

export default function MemberDashboard() {
    const { user } = useAuth();
    const [loans, setLoans] = useState(MOCK_MEMBER_LOANS);
    const [fines, setFines] = useState(MOCK_FINES);
    const [selectedFine, setSelectedFine] = useState(null);
    const [isPayModalOpen, setIsPayModalOpen] = useState(false);
    const [actionMsg, setActionMsg] = useState('');

    const handleRenew = (loanId) => {
        setLoans(prev => prev.map(l => l.id === loanId ? { ...l, renewal_count: l.renewal_count + 1, due_date: '2026-08-07' } : l));
        setActionMsg('Loan renewed successfully! Extended due date by 14 days.');
        setTimeout(() => setActionMsg(''), 4000);
    };

    const handlePayFineClick = (fine) => {
        setSelectedFine(fine);
        setIsPayModalOpen(true);
    };

    const handlePaymentSuccess = () => {
        setFines(prev => prev.map(f => f.id === selectedFine.id ? { ...f, balance: 0, status: 'paid' } : f));
        setIsPayModalOpen(false);
        setActionMsg('M-Pesa Payment Received! Fine settled successfully.');
        setTimeout(() => setActionMsg(''), 4000);
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {/* Header Banner */}
            <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <span className="text-xs font-bold text-indigo-400 uppercase tracking-wider block mb-1">
                        Member Portal • {user?.member?.member_number || 'MEM-2026'}
                    </span>
                    <h1 className="text-2xl md:text-3xl font-extrabold text-white">
                        Welcome back, {user?.name || 'Library Member'}
                    </h1>
                    <p className="text-xs text-slate-400 mt-1">
                        Tier: <span className="text-indigo-300 font-semibold capitalize">{user?.member?.membership_tier || 'student'}</span> • Active Limit: {user?.member?.borrow_limit || 5} Books
                    </p>
                </div>

                <div className="flex gap-3">
                    <div className="bg-slate-950 px-4 py-3 rounded-2xl border border-slate-800 text-center">
                        <span className="text-[10px] text-slate-500 block uppercase font-bold">Active Loans</span>
                        <span className="text-xl font-extrabold text-indigo-400">{loans.filter(l => l.status === 'active').length}</span>
                    </div>
                    <div className="bg-slate-950 px-4 py-3 rounded-2xl border border-slate-800 text-center">
                        <span className="text-[10px] text-slate-500 block uppercase font-bold">Unpaid Fines</span>
                        <span className="text-xl font-extrabold text-rose-400">
                            KES {fines.reduce((acc, f) => acc + (f.status === 'unpaid' ? f.balance : 0), 0)}
                        </span>
                    </div>
                </div>
            </div>

            {/* Action Feedback */}
            {actionMsg && (
                <div className="mb-6 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 flex items-center gap-3 animate-in fade-in">
                    <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0" />
                    <span className="text-sm font-semibold">{actionMsg}</span>
                </div>
            )}

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {/* Active Checkouts / Loans */}
                <div className="lg:col-span-2 space-y-6">
                    <div className="flex items-center justify-between">
                        <h2 className="text-lg font-bold text-white flex items-center gap-2">
                            <BookOpen className="w-5 h-5 text-indigo-400" /> My Current Checkouts
                        </h2>
                    </div>

                    <div className="space-y-4">
                        {loans.map(loan => (
                            <div key={loan.id} className="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div>
                                    <div className="flex items-center gap-2 mb-1">
                                        <span className="text-[10px] font-mono text-slate-500 bg-slate-950 px-2 py-0.5 rounded border border-slate-800">{loan.barcode}</span>
                                        {loan.status === 'overdue' ? (
                                            <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center gap-1">
                                                <AlertTriangle className="w-3 h-3" /> Overdue
                                            </span>
                                        ) : (
                                            <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                Active Loan
                                            </span>
                                        )}
                                    </div>
                                    <h3 className="font-bold text-white text-base">{loan.book_title}</h3>
                                    <p className="text-xs text-slate-400 mt-1 flex items-center gap-3">
                                        <span>Issued: {loan.loan_date}</span>
                                        <span className="font-semibold text-indigo-300">Due: {loan.due_date}</span>
                                        <span>Renewals: {loan.renewal_count}</span>
                                    </p>
                                </div>

                                <button
                                    onClick={() => handleRenew(loan.id)}
                                    className="px-4 py-2 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-semibold flex items-center gap-1.5 transition-all self-end md:self-center"
                                >
                                    <RotateCw className="w-3.5 h-3.5" /> Renew Loan
                                </button>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Fines & Payment Box */}
                <div className="space-y-6">
                    <h2 className="text-lg font-bold text-white flex items-center gap-2">
                        <CreditCard className="w-5 h-5 text-rose-400" /> Library Fines & M-Pesa
                    </h2>

                    <div className="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 space-y-4">
                        {fines.map(fine => (
                            <div key={fine.id} className="p-4 rounded-xl bg-slate-950 border border-slate-800 space-y-3">
                                <div className="flex justify-between items-start">
                                    <div>
                                        <span className="text-xs font-bold text-rose-400 block">{fine.reason}</span>
                                        <span className="text-[10px] text-slate-500">Fine ID #{fine.id}</span>
                                    </div>
                                    <span className="text-lg font-extrabold text-white">
                                        KES {fine.balance.toFixed(2)}
                                    </span>
                                </div>

                                {fine.status === 'unpaid' ? (
                                    <button
                                        onClick={() => handlePayFineClick(fine)}
                                        className="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 transition-all"
                                    >
                                        <Smartphone className="w-4 h-4" /> Pay with M-Pesa STK
                                    </button>
                                ) : (
                                    <span className="block text-center py-2 bg-emerald-500/10 text-emerald-400 font-bold text-xs rounded-xl border border-emerald-500/20">
                                        Settled & Paid
                                    </span>
                                )}
                            </div>
                        ))}
                    </div>
                </div>

            </div>

            {/* M-Pesa Modal */}
            <DarajaPayModal
                fine={selectedFine}
                isOpen={isPayModalOpen}
                onClose={() => setIsPayModalOpen(false)}
                onSuccess={handlePaymentSuccess}
            />
        </div>
    );
}
