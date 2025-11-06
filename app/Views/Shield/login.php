<?= $this->extend('layout') ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('meta') ?>
<div class="container card" >
                <h5><?= lang('Auth.login') ?></h5>

                <?php if (session('error') !== null) : ?>
                    <div><?= esc(session('error')) ?></div>
                <?php elseif (session('errors') !== null) : ?>
                    <div>
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= esc($error) ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= esc(session('errors')) ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div><?= esc(session('message')) ?></div>
                <?php endif ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div>
                        <input type="text" class="form-control" id="usernameInput" name="username" inputmode="username" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                    </div>

                    <!-- Password -->
                    <div>
                        <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                    </div>

                    <!-- Remember me -->
                    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                        <div>
                            <label>
                                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <?= lang('Auth.rememberMe') ?>
                            </label>
                        </div>
                    <?php endif; ?>

                    <div>
                        <button type="submit" class="btn-primary"><?= lang('Auth.login') ?></button>
                    </div>

                    <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                        <p><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                    <?php endif ?>

                    <?php if (setting('Auth.allowRegistration')) : ?>
                        <p><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
                    <?php endif ?>

                </form>
</div>

<?= $this->endSection() ?>
