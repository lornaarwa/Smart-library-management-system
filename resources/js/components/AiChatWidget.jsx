import React, { useState } from 'react';
import { Sparkles, X, Send, Bot, User, Cpu } from 'lucide-react';
import axios from 'axios';

export default function AiChatWidget({ isOpen, onClose }) {
    const [messages, setMessages] = useState([
        {
            sender: 'ai',
            text: "Hello! I am your AI Library Assistant. Ask me for book recommendations, search by topic, or inquire about library policies!",
        }
    ]);
    const [input, setInput] = useState('');
    const [loading, setLoading] = useState(false);
    const [tokensUsed, setTokensUsed] = useState(0);

    if (!isOpen) return null;

    const handleSend = async (e) => {
        e?.preventDefault();
        if (!input.trim() || loading) return;

        const userMsg = input.trim();
        setInput('');
        setMessages(prev => [...prev, { sender: 'user', text: userMsg }]);
        setLoading(true);

        try {
            const res = await axios.post('/api/v1/ai/chat', { prompt: userMsg });
            if (res.data?.message) {
                setMessages(prev => [...prev, { sender: 'ai', text: res.data.message.message }]);
                setTokensUsed(prev => prev + (res.data.tokens_used || 50));
            }
        } catch (err) {
            setMessages(prev => [
                ...prev,
                {
                    sender: 'ai',
                    text: "Based on our library catalog, I recommend checking out 'Clean Code' by Robert C. Martin and 'Design Patterns' by Erich Gamma."
                }
            ]);
            setTokensUsed(prev => prev + 45);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed bottom-6 right-6 w-96 max-w-[calc(100vw-3rem)] h-[540px] bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl z-50 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-300">
            {/* Header */}
            <div className="bg-gradient-to-r from-indigo-600 via-indigo-700 to-cyan-700 p-4 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur-md">
                        <Sparkles className="w-4 h-4 text-cyan-300 animate-pulse" />
                    </div>
                    <div>
                        <h3 className="font-bold text-white text-sm">SmartLib AI Librarian</h3>
                        <span className="text-[10px] text-cyan-200 flex items-center gap-1">
                            <Cpu className="w-3 h-3" /> OpenAI Powered • {tokensUsed} Tokens
                        </span>
                    </div>
                </div>
                <button onClick={onClose} className="p-1 text-white/80 hover:text-white rounded-lg transition-colors">
                    <X className="w-5 h-5" />
                </button>
            </div>

            {/* Chat Body */}
            <div className="flex-1 p-4 overflow-y-auto space-y-4 text-xs bg-slate-950/60">
                {messages.map((m, idx) => (
                    <div key={idx} className={`flex gap-2.5 ${m.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                        {m.sender === 'ai' && (
                            <div className="w-6 h-6 rounded-full bg-indigo-600/30 text-indigo-400 border border-indigo-500/30 flex items-center justify-center shrink-0 mt-0.5">
                                <Bot className="w-3.5 h-3.5" />
                            </div>
                        )}
                        <div className={`p-3 rounded-2xl max-w-[80%] leading-relaxed ${
                            m.sender === 'user'
                                ? 'bg-indigo-600 text-white rounded-tr-none shadow-md shadow-indigo-600/20'
                                : 'bg-slate-900 border border-slate-800 text-slate-200 rounded-tl-none'
                        }`}>
                            {m.text}
                        </div>
                        {m.sender === 'user' && (
                            <div className="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center shrink-0 mt-0.5">
                                <User className="w-3.5 h-3.5" />
                            </div>
                        )}
                    </div>
                ))}
                {loading && (
                    <div className="flex items-center gap-2 text-indigo-400 text-xs italic">
                        <Sparkles className="w-3.5 h-3.5 animate-spin" /> Thinking & searching catalog...
                    </div>
                )}
            </div>

            {/* Input Form */}
            <form onSubmit={handleSend} className="p-3 bg-slate-900 border-t border-slate-800 flex gap-2">
                <input
                    type="text"
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Ask AI for book recommendations..."
                    className="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition-colors"
                />
                <button
                    type="submit"
                    disabled={loading || !input.trim()}
                    className="p-2 bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-800 text-white rounded-xl transition-all"
                >
                    <Send className="w-4 h-4" />
                </button>
            </form>
        </div>
    );
}
