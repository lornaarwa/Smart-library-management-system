import React from 'react';
import { Book, Download, Bookmark, CheckCircle2, XCircle, ShieldAlert } from 'lucide-react';

export default function BookCard({ book, onReserve }) {
    const isAvailable = book.available_copies > 0 && !book.is_blocked;

    return (
        <div className="group bg-slate-900/70 rounded-2xl border border-slate-800 hover:border-indigo-500/50 p-5 flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1">
            <div>
                {/* Cover / Placeholder Banner */}
                <div className="relative aspect-[3/4] w-full rounded-xl bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-950/60 overflow-hidden flex flex-col items-center justify-center p-4 border border-slate-800/80 mb-4 group-hover:scale-[1.02] transition-transform">
                    {book.cover_image_path ? (
                        <img src={book.cover_image_path} alt={book.title} className="w-full h-full object-cover rounded-lg" />
                    ) : (
                        <div className="text-center p-4">
                            <Book className="w-12 h-12 text-indigo-400/80 mx-auto mb-2 group-hover:scale-110 transition-transform" />
                            <span className="text-xs font-semibold text-slate-400 block line-clamp-2">{book.title}</span>
                            <span className="text-[10px] text-slate-500 block mt-1">{book.author}</span>
                        </div>
                    )}

                    {/* Status Badge */}
                    <div className="absolute top-3 right-3">
                        {book.is_blocked ? (
                            <span className="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center gap-1 backdrop-blur-md">
                                <ShieldAlert className="w-3 h-3" /> Restricted
                            </span>
                        ) : isAvailable ? (
                            <span className="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1 backdrop-blur-md">
                                <CheckCircle2 className="w-3 h-3" /> Available ({book.available_copies})
                            </span>
                        ) : (
                            <span className="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1 backdrop-blur-md">
                                <XCircle className="w-3 h-3" /> On Loan
                            </span>
                        )}
                    </div>
                </div>

                {/* Info */}
                <div className="space-y-1.5">
                    <div className="flex items-center justify-between text-[11px] text-indigo-400 font-semibold tracking-wide uppercase">
                        <span>{book.genre}</span>
                        <span className="text-slate-500">ISBN: {book.isbn}</span>
                    </div>

                    <h3 className="font-bold text-slate-100 text-base group-hover:text-indigo-300 transition-colors line-clamp-1">
                        {book.title}
                    </h3>
                    <p className="text-xs text-slate-400 font-medium">By {book.author}</p>
                    {book.description && (
                        <p className="text-xs text-slate-400/80 line-clamp-2 mt-2 leading-relaxed">
                            {book.description}
                        </p>
                    )}
                </div>
            </div>

            {/* Actions */}
            <div className="mt-5 pt-4 border-t border-slate-800/80 flex items-center gap-2">
                {book.file_path && (
                    <a
                        href={book.file_path}
                        target="_blank"
                        rel="noreferrer"
                        className="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                        title="Download Digital E-Book"
                    >
                        <Download className="w-4 h-4" />
                    </a>
                )}

                <button
                    onClick={() => onReserve(book)}
                    disabled={book.is_blocked}
                    className={`flex-1 py-2.5 px-4 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition-all ${
                        book.is_blocked
                            ? 'bg-slate-800 text-slate-500 cursor-not-allowed'
                            : isAvailable
                            ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20'
                            : 'bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 border border-amber-500/30'
                    }`}
                >
                    <Bookmark className="w-3.5 h-3.5" />
                    <span>{isAvailable ? 'Reserve / Borrow' : 'Join Hold Queue'}</span>
                </button>
            </div>
        </div>
    );
}
