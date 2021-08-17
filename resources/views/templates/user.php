<div class="main-wrapper">
    <header class="main-header">
        <h1 class="h1 main-header_h1 bright-blue-neon">Keyburner</h1>
        <h2 class='h1 main-header_h2 pink-neon'><?= $_SESSION['auth_subsystem']['user_name'] ?></h2>
        <menu class="main-header-menu">
            <nav>
                <li class="blue-neon main-header-menu__item js_clean-all"><span class='pointer'>&#187;</span> Новый текст</li>
                <li class="blue-neon main-header-menu__item js_get-random-text"><span class='pointer'>&#187;</span> Случайный текст</li>
                <li class="blue-neon main-header-menu__item js_desroy"><span class='pointer'>&#187;</span> <a class="link blue-neon" href="user.php?exit=exit">Выйти</a></li>
                <li class="blue-neon-box main-header-menu__item search js_search">
                    <input class="search__input js_search-word" type="text" placeholder="Поиск">
                    <button class="search__go blue-neon js_search-button" data-tooltip="Искать" label="Искать">&#10140;</button>
                </li>
            </nav>
            <ul class="serch-result users-theme blue-neon-box js_serch-result" search_attr=""></ul>
        </menu>
        
        <div class="users-theme-wrapper blue-neon-box js_users-theme-wrapper">
            <h2 class='h1 main-header_h2 pink-neon'>Ваши темы:</h2>
            <div class="users-theme js_users-theme">
                <?php
                    use resources\views\UserTextListView;

                    $view = new UserTextListView();
                    $view->print($response);
                ?>
            </div>
        </div>
        
        <div class="default-text-list blue-neon-box js_default-text-list">
            <h2 class="default-text-list__head main-header_h2 pink-neon">Быстрый старт:</h2>
            <div class="select__wrapper blue-neon-box">
                <span class="select__arrow">&#9660;</span>
                <select class="select default-select">
                    <?php
                        $defTextCol = $response->getKeyFeedback('defaultTextCollection');

                        foreach($defTextCol as $model){
                            echo "<option 
                                class='default-text-list__name select__option blue-neon js_default-text-list__name' 
                                data-id='".$model->getId()."' 
                                name='".$model->getName()."'>
                                    <span class='js_value'>" . $model->getName() . "</span>
                                </option>";
                        }
                    ?>
                </select>
            </div>
            <p class="blue-neon main-header-menu__item js_get-random-text-default"><span class='pointer'>&#187;</span> Случайный текст</p>
        </div>
    </header> 
                
    <main class="main js-main">
        <section class="test">
            <?php
                //
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
            <div class="statistics-section__item stat-best"><span class="green-neon">Топ: </span><span class="blue-neon js_stat-best">0</span></div>
            <div class="statistics-section__item blue-neon last"><span class="">(симв. в мин.)</span></div>
            <!-- <div class="statistics-section__item blue-neon last">
                <span class='show-chart green-neon js-show-chart' data-tooltip='Показать график статистики'>&#9660;</span>
            </div> -->
        </section>
        
        <section class="section main__head main__section">
            <input class="main__name blue-neon-box js_main-name" type="text" placeholder="Название">
            <input class="main__theme-name blue-neon-box js_main-theme-name" type="text" placeholder="Тема">
            <p class="current-text-id pink-neon js_current-text-id-wrapper"></p>

            <!-- Отображение кнопки вызова графика статистики и самого окна графика -->
            <div class="graph">
                <button class="graph__button pink-neon-box js_graph-button" data-tooltip='Показать график статистики'>
                    <img src="http://94.244.191.245/keyburner50/resources/img/chart-icon.png" class="graph__ico">
                </button>
                <dialog class="dialog graph__dialog">
                    <div class="graph__inner">
                        <!-- <p class="blue-neon graph-fail js-graph-fail">Выберите текст для отображения статистики</p> -->
                        <div class="js-graph-image"><p class="bright-blue-neon">Выберите текст для отображения статистики</p></div>
                    </div>
                    <button class="graph__close pink-neon pink-neon-box js-graph__close">&#10006;</button>
                </dialog>
            </div>
        </section>
        <p class="message pink-neon">Привет!</p>
        
        <section class="section main__section js_section-template">
            <textarea class="textarea main__textarea blue-neon-box js-main-textarea js-textarea" placeholder='Добавьте ваш текст в это окно или выберите текст из списка'></textarea>
        </section>
        
        <section class="section main__section">
            <textarea class="textarea main__textarea main__work-textarea blue-neon-box js-work-textarea js-textarea" placeholder='Сначала добавьте текст в верхнее поле' autofocus disabled></textarea>
        </section>        
        
        <button class="button main__button pink-neon pink-neon-box js-del" title="Удалить">
            <img src="http://94.244.191.245/keyburner50/resources/img/del.png" class="main__ico">
        </button>
        <button class="button main__button pink-neon pink-neon-box save-button js_add-text" title="Сохранить">
            <img src="http://94.244.191.245/keyburner50/resources/img/save.png" class="main__ico main__ico-save">
        </button>
        <button class="button main__button main__button-edit pink-neon pink-neon-box js-replaceWith" title="Редактировать">
            <img src="http://94.244.191.245/keyburner50/resources/img/edit.png" class="main__ico">
        </button>
    </main>
</div>
            
<footer class="footer">
    <details class="details">
        <summary>&copy; AugCat50</summary>
        <p><a href="mailto:draackul2@gmail.com">draackul2@gmail.com</a></p>
    </details>
    <a target="_blank" href="https://www.artstation.com/antoinecollignon">Art by Antoine Collignon</a>
</footer>

<dialog class="dialog dialog_delete">
    <div class="dialog__display">
        <div class="dialog__form" method="post">
            <h3 class="dialog__h bright-blue-neon">Удалить текст?</h3>
            <div class="dialog__message pink-neon js_dialog_delete-message"></div>
            <button class="button dialog__button blue-neon-box js_dialog_delete__ready">Удалить</button>
            <button class="button dialog__button blue-neon-box js_dialog_delete__hide">Отмена</button>
        </div>
    </div>
</dialog>

<dialog class="dialog js_dialog_delete_theme">
    <div class="dialog__display">
        <div class="dialog__form" method="post">
            <h3 class="dialog__h bright-blue-neon">Удалить тему?</h3>
            <p>Все тексты и их статистика будут утеряны навсегда.</p>
            <div class="dialog__message pink-neon js_dialog_delete-message"></div>
            <button class="button dialog__button blue-neon-box js_dialog_delete_theme__ready">Удалить</button>
            <button class="button dialog__button blue-neon-box js_dialog_delete_theme__hide">Отмена</button>
        </div>
    </div>
</dialog>