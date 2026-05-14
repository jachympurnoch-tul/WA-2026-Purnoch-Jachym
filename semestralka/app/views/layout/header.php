<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>GameHub - Katalog Videoher</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/favicon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;700;900&display=swap');
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #030712 !important; /* Force Zinc 950 */
            color: #f1f5f9 !important;
        }
        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #030712; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</head>
<body class="bg-[#030712] text-slate-200 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">

    <header class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-white/5">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center group select-none">
                <!-- Gaming Logo -->
                <div class="relative w-12 h-12 flex items-center justify-center group-hover:scale-105 transition-all duration-500 shadow-[0_0_30px_rgba(89,70,227,0.4)] mr-4 rounded-[10px]">
                    <svg class="w-full h-full" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <rect style="fill:#5946e3;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="100" height="100" x="0" y="0" ry="20.705702" />
                        <rect style="fill:#ffffff;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="75" height="75" x="12.5" y="12.5" ry="15.529277" />
                        <rect style="fill:#5946e3;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="60" height="60" x="20" y="20" ry="12.423421" />
                        <path style="fill:#5946e3;fill-opacity:1;stroke:#fefefe;stroke-width:0" d="m 135.27074,53.737964 -22.83656,13.184698 a 3.8189313,3.8189313 29.999999 0 1 -5.7284,-3.307291 V 37.245973 a 3.8189313,3.8189313 150 0 1 5.7284,-3.307291 l 22.83656,13.184698 a 3.8189317,3.8189317 90 0 1 0,6.614584 z" transform="matrix(1.4766479,0,0,1.4702743,-154.04081,-25.006074)" />
                        <path style="fill:#ffffff;fill-opacity:1;stroke:#fefefe;stroke-width:0" d="m 135.27074,53.737964 -22.83656,13.184698 a 3.8189313,3.8189313 29.999999 0 1 -5.7284,-3.307291 V 37.245973 a 3.8189313,3.8189313 150 0 1 5.7284,-3.307291 l 22.83656,13.184698 a 3.8189317,3.8189317 90 0 1 0,6.614584 z" transform="matrix(0.85317432,0,0,0.91157006,-84.362325,3.1860984)" />
                        <rect style="fill:#ffffff;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="7" height="27.583725" x="47.467812" y="30.434551" ry="2.0687795" />
                        <rect style="fill:#ffffff;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="10.54068" height="6.6436548" x="41.428169" y="68.087936" ry="1.6325328" />
                        <rect style="fill:#ffffff;fill-opacity:1;stroke:#fefefe;stroke-width:0" width="7" height="27.875854" x="63.567532" y="30.600203" ry="2.0906892" />
                    </svg>
                </div>
                <!-- Brand -->
                <div class="flex flex-col">
                    <span class="text-[10px] tracking-[0.4em] text-indigo-500 font-black uppercase mb-0.5">Semestrální Práce</span>
                    <span class="text-2xl font-black tracking-tighter text-white uppercase italic leading-none">
                        Game<span class="text-indigo-500">Hub</span>
                    </span>
                </div>
            </a>

             <nav class="mt-4 md:mt-0 ml-auto">
                <ul class="flex items-center space-x-8">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=game/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-all transform hover:scale-105 hover:shadow-[0_0_15px_rgba(79,70,229,0.3)] group text-[10px] uppercase tracking-widest">
                                <svg class="w-4 h-4 mr-1.5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                Přidat Hru
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Dimmed Add Game for Guests -->
                        <li class="opacity-30 cursor-not-allowed group relative">
                            <div class="inline-flex items-center px-4 py-2 bg-slate-800/50 text-slate-500 font-bold rounded-lg text-[10px] uppercase tracking-widest border border-white/5 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                Přidat Hru
                            </div>
                            <!-- Tooltip on hover -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-slate-900 text-[9px] text-slate-400 font-black uppercase tracking-tighter rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-white/5">
                                Dostupné po přihlášení
                            </div>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php" class="text-slate-400 hover:text-white transition-colors font-black uppercase tracking-widest text-[10px]">
                            Katalog Her
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="relative group">
                            <!-- User Section -->
                            <div class="flex items-center space-x-3 cursor-pointer py-2 pl-4 border-l border-white/10">
                                <div class="w-8 h-8 rounded-full bg-indigo-600/20 border border-indigo-500/50 flex items-center justify-center text-indigo-400 font-black text-xs">
                                    <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
                                </div>
                                <span class="text-white font-bold tracking-tight">
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </span>
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>

                            <!-- Dropdown -->
                            <div class="absolute right-0 top-full w-48 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
                                    <a href="<?= BASE_URL ?>/index.php?url=user/show" class="block px-5 py-4 text-xs font-black uppercase tracking-widest text-slate-300 hover:bg-slate-800 hover:text-indigo-400 transition-colors border-b border-white/5">
                                        Můj Profil
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?url=game/create" class="block px-5 py-4 text-xs font-black uppercase tracking-widest text-slate-300 hover:bg-slate-800 hover:text-indigo-400 transition-colors border-b border-white/5">
                                        Přidat Hru
                                    </a>
                                    <?php if (!empty($_SESSION['is_admin'])): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=user/index" class="block px-5 py-4 text-xs font-black uppercase tracking-widest text-amber-500 hover:bg-slate-800 hover:text-amber-400 transition-colors border-b border-white/5">
                                            Správa Uživatelů
                                        </a>
                                    <?php endif; ?>
                                    <form method="POST" action="<?= BASE_URL ?>/index.php?url=auth/logout">
                                        <button type="submit" class="w-full text-left block px-5 py-4 text-xs font-black uppercase tracking-widest text-rose-400 hover:bg-rose-900/20 transition-colors">
                                            Odhlásit Se
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>

                    <?php else: ?>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-slate-400 hover:text-white transition-colors font-black uppercase tracking-widest text-[10px]">Přihlásit</a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="bg-white hover:bg-indigo-500 text-black hover:text-white px-6 py-3 rounded-xl transition-all font-black uppercase tracking-widest text-[10px]">
                                Registrace
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Notification System -->
    <div class="fixed bottom-8 right-8 z-[100] max-w-sm w-full space-y-4">
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                <?php 
                    $styles = [
                        'success' => 'bg-indigo-600 text-white shadow-[0_0_40px_rgba(79,70,229,0.4)]',
                        'error'   => 'bg-rose-600 text-white shadow-[0_0_40px_rgba(225,29,72,0.4)]',
                        'notice'  => 'bg-amber-600 text-white shadow-[0_0_40px_rgba(217,119,6,0.4)]',
                    ];
                    $style = $styles[$type] ?? 'bg-slate-800 text-white';
                ?>
                <?php foreach ($messages as $message): ?>
                    <div class="<?= $style ?> p-5 rounded-2xl flex items-center gap-4 animate-bounce-in notification-auto-close">
                        <div class="flex-grow">
                            <p class="font-black uppercase tracking-widest text-[10px] mb-1 opacity-70"><?= $type ?></p>
                            <p class="font-bold text-sm"><?= htmlspecialchars($message) ?></p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        <?php endif; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.notification-auto-close').forEach(function(el, i) {
            setTimeout(function() {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 500);
            }, 4000 + i * 600);
        });
    });
    </script>