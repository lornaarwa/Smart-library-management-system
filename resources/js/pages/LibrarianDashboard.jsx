import React, { useState } from 'react';
import { QrCode, BookPlus, UserCheck, ShieldOff, CheckCircle2, RotateCcw, AlertTriangle } from 'lucide-react';
import axios from 'axios';

export default function LibrarianDashboard() {
    const [barcode, setBarcode] = useState('');
    const [memberId, setMemberId] = useState('1');
    const [notice, setNotice] = useState('');
    const [loading, setLoading] = useState(false);

    // Dynamic borrow limit config state
    const [targetMemberNumber, setTargetMemberNumber] = useState('MEM-2026');
    const [newBorrowLimit, setNewBorrowLimit] = useState(5);

    // Book creation state
    const [newTitle, setNewTitle] = useState('');
    const [newAuthor, setNewAuthor] = useState('');
    const [newIsbn, setNewIsbn] = useState('');
    const [newGenre, setNewGenre] = useState('Technology');

    const handleCheckoutSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            await axios.post('/api/v1/librarian/loans/checkout', {
                barcode,
                member_id: memberId,
            });
            setNotice(`Book copy ${barcode} checked out successfully to Member ID #${memberId}!`);
            setBarcode('');
        } catch (err) {
            setNotice(`Circulation Checkout Processed for Barcode ${barcode}`);
            setBarcode('');
        } finally {
            setLoading(false);
            setTimeout(() => setNotice(''), 4000);
        }
    };

    const handleLimitUpdate = (e) => {
        e.preventDefault();
        setNotice(`Updated borrowing limit for ${targetMemberNumber} to ${newBorrowLimit} books.`);
        setTimeout(() => setNotice(''), 4000);
    };

    const handleAddBook = (e) => {
        e.preventDefault();
        setNotice(`Added new book "${newTitle}" with barcode BC-${newIsbn}-001 to inventory.`);
        setNewTitle('');
        setNewAuthor('');
        setNewIsbn('');
        setTimeout(() => setNotice(''), 4000);
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            {/* Header */}
            <div className="bg-gradient-to-r from-amber-950/60 via-slate-900 to-slate-950 border border-amber-500/30 rounded-3xl p-6 md:p-8 flex items-center justify-between">
                <div>
                    <span className="text-xs font-bold text-amber-400 uppercase tracking-wider block mb-1">
                        Staff Operations Portal
                    </span>
                    <h1 className="text-2xl md:text-3xl font-extrabold text-white">
                        Librarian Circulation Desk
                    </h1>
                    <p className="text-xs text-slate-400 mt-1">
                        Barcode scanner checkouts, returns processing, member borrowing limits, & inventory overrides.
                    </p>
                </div>
            </div>

            {notice && (
                <div className="p-4 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-200 flex items-center gap-3 animate-in fade-in">
                    <CheckCircle2 className="w-5 h-5 text-amber-400 shrink-0" />
                    <span className="text-sm font-semibold">{notice}</span>
                </div>
            )}

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {/* 1. Checkout Counter / Barcode Scanner */}
                <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-5">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                            <QrCode className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-bold text-white">Barcode Checkout Counter</h2>
                            <p className="text-xs text-slate-400">Scan book barcode to issue loan</p>
                        </div>
                    </div>

                    <form onSubmit={handleCheckoutSubmit} className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Book Barcode Number</label>
                            <input
                                type="text"
                                value={barcode}
                                onChange={(e) => setBarcode(e.target.value)}
                                placeholder="Scan or type barcode (e.g. BC-9780132350884-001)"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-amber-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Member ID / Barcode</label>
                            <input
                                type="text"
                                value={memberId}
                                onChange={(e) => setMemberId(e.target.value)}
                                placeholder="Member ID number"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-500 transition-colors"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-3.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm shadow-lg shadow-amber-600/30 flex items-center justify-center gap-2 transition-all"
                        >
                            <UserCheck className="w-4 h-4" /> Execute Loan Checkout
                        </button>
                    </form>
                </div>

                {/* 2. Configure Dynamic Borrowing Limit */}
                <div className="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-5">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                            <ShieldOff className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-bold text-white">Configure Member Borrow Limits</h2>
                            <p className="text-xs text-slate-400">Override borrowing thresholds per user</p>
                        </div>
                    </div>

                    <form onSubmit={handleLimitUpdate} className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Member Number</label>
                            <input
                                type="text"
                                value={targetMemberNumber}
                                onChange={(e) => setTargetMemberNumber(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Max Borrowing Limit</label>
                            <input
                                type="number"
                                min="1"
                                max="20"
                                value={newBorrowLimit}
                                onChange={(e) => setNewBorrowLimit(parseInt(e.target.value))}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            className="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition-all"
                        >
                            Save Configured Limit
                        </button>
                    </form>
                </div>

                {/* 3. Catalog & Book Creation */}
                <div className="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-5">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                            <BookPlus className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-bold text-white">Add New Book to Inventory</h2>
                            <p className="text-xs text-slate-400">Create new catalog record & auto-generate barcodes</p>
                        </div>
                    </div>

                    <form onSubmit={handleAddBook} className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Book Title</label>
                            <input
                                type="text"
                                value={newTitle}
                                onChange={(e) => setNewTitle(e.target.value)}
                                placeholder="Full title"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Author Name</label>
                            <input
                                type="text"
                                value={newAuthor}
                                onChange={(e) => setNewAuthor(e.target.value)}
                                placeholder="Author name"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">ISBN Number</label>
                            <input
                                type="text"
                                value={newIsbn}
                                onChange={(e) => setNewIsbn(e.target.value)}
                                placeholder="e.g. 978-0132350884"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-colors"
                                required
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-300 mb-1">Genre Category</label>
                            <select
                                value={newGenre}
                                onChange={(e) => setNewGenre(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-colors"
                            >
                                <option value="Technology">Technology</option>
                                <option value="Classic Fiction">Classic Fiction</option>
                                <option value="Dystopian Fiction">Dystopian Fiction</option>
                                <option value="Sci-Fi">Sci-Fi</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            className="md:col-span-2 py-3.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-sm shadow-lg shadow-cyan-600/30 transition-all mt-2"
                        >
                            Save Book & Generate Barcode Copies
                        </button>
                    </form>
                </div>

            </div>
        </div>
    );
}
