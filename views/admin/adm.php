<?php

	/** @var yii\web\View $this */

	$this->title = 'Администраторская';
	$this->params['breadcrumbs'][] = $this->title;
?>

<body>
<main>
    <h1 class="visually-hidden">Примеры функций</h1>

    <div class="container px-4 py-5" id="hanging-icons">
        <h2 class="pb-2 border-bottom">Администрирование</h2>
        <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                    <svg class="bi" width="1em" height="1em"><use xlink:href="#toggles2"></use></svg>
                </div>
                <div>

                    <a href="../admin">
                        <h2>Модуль пользователи</h2>
                    </a>
                    <p>Это компонент, который управляет информацией о пользователях на веб-сайте.</p>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                    <svg class="bi" width="1em" height="1em"><use xlink:href="#cpu-fill"></use></svg>
                </div>
                <div>
                    <a href="../post">
                        <h2>Модуль контент</h2>
                    </a>
                    <p>Это компонент, который позволяет пользователям создавать, управлять и публиковать контент на веб-сайте.</p>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                    <svg class="bi" width="1em" height="1em"><use xlink:href="#tools"></use></svg>
                </div>
                <div>
                    <a href="../admin/statistics">
                        <h2>Модуль статистики</h2>
                    </a>
                    <p>Статистика постов может помочь вам понять, какой контент наиболее эффективен для вашей аудитории. </p>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                    <svg class="bi" width="1em" height="1em"><use xlink:href="#tools"></use></svg>
                </div>
                <div>
                    <a href="../admin/category">
                        <h2>Модуль категории</h2>
                    </a>
                    <p>Модуль категории страниц (пополняемый справочник). </p>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="../../web/Функции · Bootstrap v5.0_files/bootstrap.bundle.min.js.Без названия" crossorigin="anonymous"></script>

</body>




