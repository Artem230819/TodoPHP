<?php
?>
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>
                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($data_project as $value) : ?>
                            <li class="main-navigation__list-item <?php if ((int)$_GET['id'] === $value['id']){echo 'main-navigation__list-item--active';} ?>">
                                <a class="main-navigation__list-item-link" href="/index.php?id=<?= $value['id'] ?>"><?=$value['name']?></a>
                                <span class="main-navigation__list-item-count"><?=$value['count']?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="/add-project.php" target="project_add">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="/index.php" method="get" autocomplete="off">
                    <input class="search-form__input" type="text" name="search" value="<?php echo isset($_GET['search'])? strip_tags($_GET['search']): '' ?>" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/index.php" class="tasks-switch__item <?= !isset($_GET['taskFilter']) ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
                        <a href="/index.php?taskFilter=today" class="tasks-switch__item <?= isset($_GET['taskFilter']) && $_GET['taskFilter'] === 'today' ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
                        <a href="/index.php?taskFilter=tomorrow" class="tasks-switch__item <?= isset($_GET['taskFilter']) && $_GET['taskFilter'] === 'tomorrow' ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                        <a href="/index.php?taskFilter=overdue" class="tasks-switch__item <?= isset($_GET['taskFilter']) && $_GET['taskFilter'] === 'overdue' ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                            <?php
                            echo (!empty($show_complete_tasks)) ?  'checked' : '';
                            ?>
                        >
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

                <table class="tasks">
                    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
                    <?php foreach ($data_task as $kay => $value) : ?>
                        <form class="form"  action="/index.php" method="post" autocomplete="off">
                        <? if(!empty($_GET['id'])){ if ((int)$_GET['id'] ==! $value['categories_id']){ continue; } } ?>
                        <?  if (!empty($value['status']) && empty($show_complete_tasks)){ continue; } ?>
                            <tr class="tasks__item task
                            <?php
                            if( !empty($value['status'])){echo 'task--completed'; }
                            ?>
                            <?php
                            if ($resultDay($value) <= 0 && $value['date_completion'] !== null && empty($value['status'])){ echo 'task--important';}
                            ?>
    ">
                                <td class="task__select">
                                    <label class="checkbox task__checkbox">
                                        <input class="checkbox__input visually-hidden" onchange="form.submit()" type="checkbox" <?php echo !empty($value['status']) ? 'checked' : '' ?>>
                                        <span class="checkbox__text"><?=$value['task'] ?></span>
                                        <input type="hidden" name="mark" value="<?= $value['task_id'] ?>">
                                    </label>
                                </td>
    <!--                            <td class="task__file"><a  href="#"></a></td>-->
                                <?= $value['file'] ? ' <td class="task__file"><a class="download-link" href="'.$value['file'] .'">'.substr($value['file'], 23).'</a></td>' :
                                    '<td class="task__file"></td>'?>
                                <td class="task__date"><?= $value['date_completion'] ?></td>
                                <td class="task__controls"></td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                    <?php if (empty($data_task) && isset($_GET['search'])){echo '<p> Ничего не найдено по вашему запросу </p>';} ?>

                </table>
            </main>
