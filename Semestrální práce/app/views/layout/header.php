<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Knihovna - Výuková aplikace</title>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen font-sans flex flex-col">

    <header class="bg-slate-900 border-b border-slate-800 shadow-xl">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center group select-none">
                <!-- Závodně-knižní SVG Logo -->
                <div class="relative min-w-[48px] h-12 flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl transform -skew-x-12 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-[0_0_20px_rgba(249,115,22,0.4)] mr-5 border-b-2 border-orange-700">
                    <svg class="w-7 h-7 text-white transform skew-x-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <!-- Vymakaná typografie nadpisu -->
                <h1 class="flex flex-col justify-center">
                    <span class="text-[0.65rem] tracking-[0.35em] text-slate-400 font-black uppercase mb-0.5 opacity-80 group-hover:opacity-100 transition-opacity">Výukový projekt</span>
                    <span class="text-3xl font-black tracking-tight text-white uppercase italic leading-none group-hover:text-orange-50 transition-colors">
                        Knihovna<span class="text-orange-500 not-italic ml-0.5 animate-pulse">.</span>
                    </span>
                </h1>
            </a>
            
            <nav class="mt-4 md:mt-0">
                <ul class="flex space-x-6">
                    <li>
                        <a href="<?= BASE_URL ?>/index.php" class="text-slate-300 hover:text-orange-500 transition-colors font-medium">Seznam knih</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=book/create" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md transition-all shadow-inner border border-orange-600">
                            + Přidat knihu
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-6 pt-8">
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="space-y-3">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php 
                        $styles = [
                            'success' => 'bg-emerald-900/30 border-emerald-500 text-emerald-400',
                            'error'   => 'bg-rose-900/30 border-rose-500 text-rose-400',
                            'notice'  => 'bg-amber-900/30 border-amber-500 text-amber-400',
                        ];
                        $style = $styles[$type] ?? 'bg-slate-800 border-slate-500 text-slate-300';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="<?= $style ?> border-l-4 p-4 rounded-r-lg shadow-md animate-fade-in">
                            <p class="font-semibold text-sm italic"><?= htmlspecialchars($message) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']); ?>
            </div>
        <?php endif; ?>
    </div>