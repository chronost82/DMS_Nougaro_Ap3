<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
    <title><?= esc($title ?? 'Atelier MV') ?></title>
</head>

<div class="container" style="max-width:560px;">
    <div class="card form-panel">
        <h1 style="margin:0 0 .75rem 0; font-size:1.25rem;"><?= lang('Auth.login') ?></h1>

        <?php if (session('error') !== null) : ?>
            <div class="warning"><?= esc(session('error')) ?></div><br>
        <?php elseif (session('errors') !== null) : ?>
            <div class="warning">
                <?php if (is_array(session('errors'))) : ?>
                    <?php foreach (session('errors') as $error) : ?>
                        <?= esc($error) ?><br>
                    <?php endforeach ?>
                <?php else : ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div><br>
        <?php endif ?>

        <?php if (session('message') !== null) : ?>
            <div class="success"><?= esc(session('message')) ?></div><br>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <div class="form-grid-2">
                <label style="flex-direction: column" for="usernameInput"><?= lang('Auth.username') ?>
                    <input type="text" id="usernameInput" name="username" inputmode="username" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                </label>
                <label style="flex-direction: column" for="passwordInput"><?= lang('Auth.password') ?>
                    <input type="password" id="passwordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                </label>
            </div>

            <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <label style="flex-direction:row; align-items:center; gap:.5rem; text-transform:none; font-weight:500;">
                    <input type="checkbox" name="remember" <?php if (old('remember')): ?> checked<?php endif ?>>
                    <?= lang('Auth.rememberMe') ?>
                </label>
            <?php endif; ?>

            <div class="form-footer">
                <button type="submit" class="btn-primary"><?= lang('Auth.login') ?></button>
            </div>

            <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                <p class="muted" style="margin-top:.5rem;">
                    <?= lang('Auth.forgotPassword') ?>
                    <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a>
                </p>
            <?php endif ?>

            <?php if (setting('Auth.allowRegistration')) : ?>
                <p class="muted" style="margin-top:.25rem;">
                    <?= lang('Auth.needAccount') ?>
                    <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a>
                </p>
            <?php endif ?>
        </form>
    </div>
</div>
