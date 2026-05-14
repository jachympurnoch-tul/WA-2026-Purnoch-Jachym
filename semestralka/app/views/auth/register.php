<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full">
        <div class="bg-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl">
            <div class="text-center mb-7">
                <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                    Nová <span class="text-indigo-500">Registrace</span>
                </h2>
                <p class="text-slate-500 mt-1.5 font-medium italic text-sm">Připojte se ke komunitě GameHub.</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="username" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Uživatelské jméno *</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Emailová adresa *</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="first_name" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Jméno</label>
                        <input type="text" id="first_name" name="first_name" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label for="last_name" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Příjmení</label>
                        <input type="text" id="last_name" name="last_name" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="nickname" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Přezdívka (veřejná)</label>
                        <input type="text" id="nickname" name="nickname" 
                               class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <!-- Heslo -->
                    <div class="md:col-span-2">
                        <label for="password" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Heslo *</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required 
                                   class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 pr-12 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all password-input">
                            <button type="button" onclick="togglePassword('password', 'eye-password')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-400 transition-colors focus:outline-none"
                                    title="Zobrazit/skrýt heslo">
                                <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Password Requirements Visualizer (kompaktní) -->
                        <div id="password-checklist" class="mt-2 grid grid-cols-2 gap-1.5 p-3 bg-slate-800/50 rounded-xl border border-slate-700/50">
                            <div id="req-length" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-3.5 h-3.5 mr-1.5 rounded-full border-2 border-slate-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-1.5 h-1.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Min. 8 znaků
                            </div>
                            <div id="req-case" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-3.5 h-3.5 mr-1.5 rounded-full border-2 border-slate-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-1.5 h-1.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Velká písmena
                            </div>
                            <div id="req-number" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-3.5 h-3.5 mr-1.5 rounded-full border-2 border-slate-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-1.5 h-1.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Alespoň 1 číslice
                            </div>
                            <div id="req-match" class="flex items-center text-[10px] font-black uppercase tracking-wider text-slate-500 transition-all duration-300">
                                <span class="indicator w-3.5 h-3.5 mr-1.5 rounded-full border-2 border-slate-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-1.5 h-1.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                Hesla se shodují
                            </div>
                        </div>
                    </div>

                    <!-- Potvrzení hesla -->
                    <div class="md:col-span-2">
                        <label for="password_confirm" class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1.5">Potvrzení hesla *</label>
                        <div class="relative">
                            <input type="password" id="password_confirm" name="password_confirm" required 
                                   class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 pr-12 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <button type="button" onclick="togglePassword('password_confirm', 'eye-confirm')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-400 transition-colors focus:outline-none"
                                    title="Zobrazit/skrýt heslo">
                                <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest py-4 rounded-2xl transition-all shadow-xl hover:shadow-[0_10px_40px_rgba(79,70,229,0.4)]">
                        VYTVOŘIT ÚČET
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800 text-center">
                <p class="text-slate-500 text-sm font-medium">Již máte účet?</p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-indigo-400 hover:text-white font-bold transition-colors">Přihlásit se</a>
            </div>
        </div>
    </div>
</main>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.innerHTML = isPassword
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}

const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirm');

const requirements = {
    length: { element: document.getElementById('req-length'), regex: /.{8,}/ },
    case:   { element: document.getElementById('req-case'),   regex: /[A-Z]/ },
    number: { element: document.getElementById('req-number'), regex: /[0-9]/ }
};

function updateChecklist() {
    const val = passwordInput.value;
    const confirmVal = confirmInput.value;

    Object.keys(requirements).forEach(key => {
        const req = requirements[key];
        updateUI(req.element, req.regex.test(val));
    });

    const isMatch = val && val === confirmVal;
    updateUI(document.getElementById('req-match'), isMatch);

    const allValid = Object.values(requirements).every(r => r.regex.test(val)) && isMatch;
    if (val.length > 0) {
        passwordInput.style.boxShadow = allValid ? '0 0 20px rgba(34, 197, 94, 0.2)' : '0 0 20px rgba(239, 68, 68, 0.2)';
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

<?php require_once '../app/views/layout/footer.php'; ?>