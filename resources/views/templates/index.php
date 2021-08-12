<div class="main-wrapper">
    <header class="main-header">
        <h1 class="h1 main-header_h1 bright-blue-neon">Keyburner</h1>

        <menu class="main-header-menu js_main-header-menu">
            <nav class="">
                <li class="blue-neon main-header-menu__item js_clean-all"><span class='pointer'>&#187;</span> Новый текст</li>
                <li class="blue-neon main-header-menu__item js_get-random-text-default"><span class='pointer'>&#187;</span> Случайный текст</li>
                <li class="blue-neon main-header-menu__item js_check-in__show"><span class='pointer'>&#187;</span> Регистрация</li>
                <li class="blue-neon main-header-menu__item js_authorization__show"><span class='pointer'>&#187;</span> Вход</li>
<!--                            <li class="blue-neon main-header-menu__item"><span class='pointer'>&#187;</span> Добавить текст</li>-->
            </nav>
        </menu>

        <ul class="default-text-list js_ul_list js_default-text-list">
            <h3 class="default-text-list__head pink-neon">Быстрый старт:</h3>
            <?php
                //Точка входа. Так же вывод списка по роуту "/" - DefaultTextHttpCommand --- DefaultTextListView
                echo $str;
            ?>
        </ul>
    </header> 
                
    <main class="main js-main">
        <section class="test">
                    <?php
                        //Переменная из DefaultTextListView
                        // echo $str;
                    ?>
        </section>

        <section class="section statistics-section main__statistics">
            <div class="statistics-section__item first">
                <span class="bright-blue-neon">Последний результат:</span>
            </div>
            
            <div class="statistics-section__item">
                <span class="pink-neon">Время: </span>
                <span class="blue-neon js-minute">00</span><span class="blue-neon">:</span><span class="blue-neon js-second">00</span>
            </div>
            <div class="statistics-section__item">
                <span class="pink-neon">Скорость: </span>
                <span class="blue-neon js-speed">0</span>
            </div>
            <div class="statistics-section__item">
                <span class="pink-neon">Ошибок: </span>
                <span class="blue-neon js-errors">0</span>
            </div>
            <div class="statistics-section__item">
                <span class="pink-neon">Штраф: </span>
                <span class="blue-neon js-penalty">0</span>
            </div>
            <div class="statistics-section__item blue-neon last"><span class="">(симв. в мин.)</span></div>
        </section>
        
        <section class="section main__section js_section-template">
            <textarea class="textarea main__textarea blue-neon-box js-main-textarea js-textarea" placeholder='Добавьте ваш текст в это окно или выберите текст из списка'></textarea>
        </section>
        
        <section class="section main__section">
            <textarea class="textarea main__textarea main__work-textarea blue-neon-box js-work-textarea js-textarea" placeholder='Сначала добавьте текст в верхнее поле' autofocus disabled></textarea>
        </section>
        
<!--                 <button class="button pink-neon pink-neon-box test-button" id="test">Test</button>-->
        <button class="button main__button pink-neon pink-neon-box js-replaceWith" title="Редактировать"><img src="resources/img/edit.png" class="main__ico"></button>
    </main>
</div>
            
<footer class="footer">
    <details class="details">
        <summary>&copy; AugCat50</summary>
        <p><a href="mailto:draackul2@gmail.com">draackul2@gmail.com</a></p>
    </details>
    <a target="_blank" href="https://www.artstation.com/antoinecollignon">Art by Antoine Collignon</a>
</footer>

<dialog class="dialog authorization">
    <div class="dialog__display">
        <form class="dialog__form" method="post">
            <h3 class="dialog__h bright-blue-neon">Авторизация</h3>
            <input name="user" class="dialog__input blue-neon-box js_log-in-name" type="text" placeholder="Login">
            <input name="password" class="dialog__input blue-neon-box js_log-in-password" type="password" placeholder="Password">
            <div class="dialog__message pink-neon"></div>
            <button class="button dialog__button blue-neon-box js_log-in-ready">Войти</button>
            <button class="button dialog__button blue-neon-box js_authorization__hide">Отмена</button>
        </form>
    </div>
</dialog>

<dialog class="dialog check-in">
    <div class="dialog__display">
        <form class="dialog__form" method="post">
            <h3 class="dialog__h bright-blue-neon">Регистрация</h3>
            <input name="user" class="dialog__input blue-neon-box js_check-in-name" type="text" placeholder="Login">
            <input name="password" class="dialog__input blue-neon-box js_check-in-password1" type="text" placeholder="Password">
            <input name="password-2" class="dialog__input blue-neon-box js_check-in-password2" type="text" placeholder="Repeat password">
            <input name="mail" class="dialog__input blue-neon-box js_check-in-mail" type="email" placeholder="Email">
            <div class="dialog__message pink-neon js_check-in-message"></div>
            <button class="button dialog__button blue-neon-box js_check-in-ready">Регистрация</button>
            <button class="button dialog__button blue-neon-box js_check-in__hide">Закрыть</button>
        </form>
    </div>
</dialog>
