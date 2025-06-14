<?php
namespace PHPSTORM_META {
    override(
        \think\annotation\Route::class,
        map([
            '' => '@',
        ])
    );
}