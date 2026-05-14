<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full">
        <div class="bg-slate-900 rounded-3xl p-10 border border-slate-800 shadow-2xl">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                    Nová <span class="text-indigo-500">Registrace</span>
                </h2>
                <p class="text-slate-500 mt-2 font-medium italic">Připojte se ke komunitě GameHub.</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="username" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Uživatelské jméno *</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Emailová adresa *</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="first_name" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Jméno</label>
                        <input type="text" id="first_name" name="first_name" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="last_name" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Příjmení</label>
                        <input type="text" id="last_name" name="last_name" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="nickname" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Přezdívka (veřejná)</label>
                        <input type="text" id="nickname" name="nickname" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="password" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Heslo *</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all password-input">
                        
                        <!-- Password Requirements Visualizer -->
                        <div id="password-checklist" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50">
                            <div id="req-length" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-4 h-4 mr-2 rounded-full border-2 border-slate-700 flex items-center justify-center">
                                    <svg class="w-2 h-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Min. 8 znaků
                            </div>
                            <div id="req-case" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-4 h-4 mr-2 rounded-full border-2 border-slate-700 flex items-center justify-center">
                                    <svg class="w-2 h-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Velká a malá písmena
                            </div>
                            <div id="req-number" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-4 h-4 mr-2 rounded-full border-2 border-slate-700 flex items-center justify-center">
                                    <svg class="w-2 h-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Alespoň 1 číslice
                            </div>
                            <div id="req-match" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-4 h-4 mr-2 rounded-full border-2 border-slate-700 flex items-center justify-center">
                                    <svg class="w-2 h-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Hesla se shodují
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="password_confirm" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Potvrzení hesla *</label>
                        <input type="password" id="password_confirm" name="password_confirm" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <script>
                    const passwordInput = document.getElementById('password');
                    const confirmInput = document.getElementById('password_confirm');
                    
                    const requirements = {
                        length: { element: document.getElementById('req-length'), regex: /.{8,}/ },
                        case: { element: document.getElementById('req-case'), regex: /^(?=.*[a-z])(?=.*[A-Z]).+$/ },
                        number: { element: document.getElementById('req-number'), regex: /[0-9]/ }
                    };

                    function updateChecklist() {
                        const val = passwordInput.value;
                        const confirmVal = confirmInput.value;

                        // Check basic regex requirements
                        Object.keys(requirements).forEach(key => {
                            const req = requirements[key];
                            const isValid = req.regex.test(val);
                            updateUI(req.element, isValid);
                        });

                        // Check match requirement
                        const isMatch = val && val === confirmVal;
                        updateUI(document.getElementById('req-match'), isMatch);

                        // Dynamic Glow
                        const allValid = Object.values(requirements).every(r => r.regex.test(val)) && isMatch;
                        if (val.length > 0) {
                            passwordInput.style.boxShadow = allValid 
                                ? '0 0 20px rgba(34, 197, 94, 0.2)' 
                                : '0 0 20px rgba(239, 68, 68, 0.2)';
                            passwordInput.style.borderColor = allValid ? '#22c55e' : '#ef4444';
                        } else {
                            passwordInput.style.boxShadow = 'none';
                            passwordInput.style.borderColor = '';
                        }
                    }

                    function updateUI(element, isValid) {
                        const indicator = element.querySelector('.indicator');
                        const icon = indicator.querySelector('svg');

                        if (isValid) {
                            element.classList.remove('text-slate-500');
                            element.classList.add('text-emerald-400');
                            indicator.classList.remove('border-slate-700');
                            indicator.classList.add('border-emerald-500', 'bg-emerald-500/20');
                            icon.classList.remove('hidden');
                        } else {
                            element.classList.add('text-slate-500');
                            element.classList.remove('text-emerald-400');
                            indicator.classList.add('border-slate-700');
                            indicator.classList.remove('border-emerald-500', 'bg-emerald-500/20');
                            icon.classList.add('hidden');
                        }
                    }

                    passwordInput.addEventListener('input', updateChecklist);
                    confirmInput.addEventListener('input', updateChecklist);
                </script>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest py-5 rounded-2xl transition-all shadow-xl hover:shadow-[0_10px_40px_rgba(79,70,229,0.4)]">
                        VYTVOŘIT ÚČET
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-800 text-center">
                <p class="text-slate-500 text-sm font-medium">Již máte účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-indigo-400 hover:text-white font-bold transition-colors">Přihlásit se</a>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>