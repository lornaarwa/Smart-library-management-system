import React, { useState, useEffect } from 'react';
import BookCard from '../components/BookCard';
import { Search, Filter, BookOpen, Layers, CheckCircle2 } from 'lucide-react';
import axios from 'axios';

const MOCK_BOOKS = [
    { id: 1, isbn: '978-0132350884', title: 'Clean Code: A Handbook of Agile Software Craftsmanship', author: 'Robert C. Martin', genre: 'Technology', available_copies: 3, total_copies: 5, is_blocked: false, description: 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees.' },
    { id: 2, isbn: '978-0201616224', title: 'The Pragmatic Programmer: Your Journey To Mastery', author: 'Andrew Hunt & David Thomas', genre: 'Technology', available_copies: 0, total_copies: 4, is_blocked: false, description: 'One of the most significant books in software development for pragmatic career growth.' },
    { id: 3, isbn: '978-0743273565', title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', genre: 'Classic Fiction', available_copies: 2, total_copies: 2, is_blocked: false, description: 'A tragic story of Jay Gatsby, a self-made millionaire, and his pursuit of Daisy Buchanan.' },
    { id: 4, isbn: '978-0451524935', title: '1984', author: 'George Orwell', genre: 'Dystopian Fiction', available_copies: 1, total_copies: 3, is_blocked: false, description: 'Winston Smith wrestles with oppression in Oceania, a place where the Party scrutinizes human actions.' },
    { id: 5, isbn: '978-0593099322', title: 'Dune', author: 'Frank Herbert', genre: 'Sci-Fi', available_copies: 4, total_copies: 6, is_blocked: false, description: 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides.' },
    { id: 6, isbn: '978-0134685991', title: 'Effective Java', author: 'Joshua Bloch', genre: 'Technology', available_copies: 0, total_copies: 2, is_blocked: true, description: 'Restricted administrative copy for faculty reference.' }
];

export default function PublicCatalog() {
    const [books, setBooks] = useState(MOCK_BOOKS);
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedGenre, setSelectedGenre] = useState('All');
    const [availableOnly, setAvailableOnly] = useState(false);
    const [reservationNotice, setReservationNotice] = useState('');

    const genres = ['All', 'Technology', 'Classic Fiction', 'Dystopian Fiction', 'Sci-Fi'];

    useEffect(() => {
        // Try fetching real API data if available
        axios.get('/api/v1/catalog/search').then(res => {
            if (res.data?.data && res.data.data.length > 0) {
                setBooks(res.data.data);
            }
        }).catch(() => {});
    }, []);

    const filteredBooks = books.filter(b => {
        const matchesSearch = b.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
                              b.author.toLowerCase().includes(searchQuery.toLowerCase()) ||
                              b.isbn.includes(searchQuery);
        const matchesGenre = selectedGenre === 'All' || b.genre === selectedGenre;
        const matchesAvailability = !availableOnly || (b.available_copies > 0 && !b.is_blocked);
        return matchesSearch && matchesGenre && matchesAvailability;
    });

    const handleReserve = (book) => {
        setReservationNotice(`Hold request placed for "${book.title}". You will be notified via email when ready!`);
        setTimeout(() => setReservationNotice(''), 4000);
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {/* Notification Banner */}
            {reservationNotice && (
                <div className="mb-6 p-4 rounded-2xl bg-indigo-500/20 border border-indigo-500/40 text-indigo-200 flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                    <CheckCircle2 className="w-5 h-5 text-indigo-400 shrink-0" />
                    <span className="text-sm font-semibold">{reservationNotice}</span>
                </div>
            )}

            {/* Hero Section */}
            <div className="relative rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-950 border border-indigo-500/20 p-8 md:p-12 mb-10 overflow-hidden">
                <div className="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none" />
                <div className="relative max-w-2xl">
                    <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 mb-4">
                        <BookOpen className="w-3.5 h-3.5" /> Online Public Access Catalog (OPAC)
                    </span>
                    <h1 className="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">
                        Discover & Borrow <span className="bg-gradient-to-r from-indigo-400 to-cyan-300 bg-clip-text text-transparent">Library Books</span>
                    </h1>
                    <p className="text-slate-400 text-sm md:text-base leading-relaxed">
                        Search our real-time catalog across physical inventory and digital e-books. Check copy availability, reserve queue spots, or get recommendations from our AI Assistant.
                    </p>
                </div>
            </div>

            {/* Search & Filter Bar */}
            <div className="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-2xl p-4 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between shadow-xl">
                
                {/* Keyword Search */}
                <div className="relative flex-1 w-full">
                    <Search className="w-5 h-5 text-slate-400 absolute left-4 top-3.5" />
                    <input
                        type="text"
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        placeholder="Search by Title, Author, Genre, or ISBN..."
                        className="w-full bg-slate-950 border border-slate-800 rounded-xl pl-12 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-colors"
                    />
                </div>

                {/* Genre Selector */}
                <div className="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                    <Filter className="w-4 h-4 text-slate-400 shrink-0 hidden md:block" />
                    {genres.map((genre) => (
                        <button
                            key={genre}
                            onClick={() => setSelectedGenre(genre)}
                            className={`px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all ${
                                selectedGenre === genre
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30'
                                    : 'bg-slate-950 text-slate-400 hover:text-white border border-slate-800'
                            }`}
                        >
                            {genre}
                        </button>
                    ))}
                </div>

                {/* Available Only Toggle */}
                <label className="flex items-center gap-2 text-xs font-semibold text-slate-300 cursor-pointer shrink-0">
                    <input
                        type="checkbox"
                        checked={availableOnly}
                        onChange={(e) => setAvailableOnly(e.target.checked)}
                        className="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900"
                    />
                    <span>Available Copies Only</span>
                </label>

            </div>

            {/* Catalog Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {filteredBooks.map((book) => (
                    <BookCard key={book.id} book={book} onReserve={handleReserve} />
                ))}
            </div>

            {filteredBooks.length === 0 && (
                <div className="text-center py-16 bg-slate-900/40 rounded-3xl border border-slate-800/80">
                    <Layers className="w-12 h-12 text-slate-600 mx-auto mb-3" />
                    <h3 className="text-lg font-bold text-white">No Books Found</h3>
                    <p className="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                        Try adjusting your search keywords or clearing genre filters.
                    </p>
                </div>
            )}
        </div>
    );
}
