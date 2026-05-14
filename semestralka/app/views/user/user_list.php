<?php require_once '../app/views/layout/header.php'; ?>

<main class="flex-grow">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl md:text-4xl font-black text-white leading-none uppercase italic tracking-tighter">
                Správa <span class="text-indigo-500">Uživatelů</span>
            </h2>
        </div>

        <div class="bg-slate-900 rounded-[2rem] overflow-hidden shadow-2xl border border-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="text-[10px] uppercase tracking-widest text-slate-500 bg-slate-950">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-black">ID</th>
                            <th scope="col" class="px-6 py-4 font-black">Uživatel</th>
                            <th scope="col" class="px-6 py-4 font-black">E-mail</th>
                            <th scope="col" class="px-6 py-4 font-black">Registrace</th>
                            <th scope="col" class="px-6 py-4 font-black text-right">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 font-bold text-slate-500">
                                    #<?= $u['id'] ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600/20 border border-indigo-500/50 flex items-center justify-center text-indigo-400 font-black text-xs">
                                            <?= strtoupper(substr($u['nickname'] ?: $u['username'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="text-white font-bold tracking-tight"><?= htmlspecialchars($u['nickname'] ?: $u['username']) ?></p>
                                            <?php if ($u['nickname']): ?>
                                                <p class="text-[9px] uppercase tracking-widest text-slate-500">@<?= htmlspecialchars($u['username']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    <?= htmlspecialchars($u['email']) ?>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    <?= date('d.m.Y', strtotime($u['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $u['id'] ?>" class="p-2 bg-indigo-600/10 text-indigo-400 hover:bg-indigo-600 hover:text-white rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/index.php?url=user/delete/<?= $u['id'] ?>" onsubmit="return confirm('Opravdu chcete trvale smazat tohoto uživatele?')" style="display:inline">
                                                <button type="submit" class="p-2 bg-rose-600/10 text-rose-400 hover:bg-rose-600 hover:text-white rounded-lg transition-colors" title="Smazat uživatele">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
