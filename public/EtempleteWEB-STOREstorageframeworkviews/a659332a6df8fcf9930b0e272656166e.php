# <?php
use Illuminate\Support\Str;
use Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys;
use Livewire\Mechanisms\ExtendBlade\ExtendBlade;

echo e($exception->class()); ?> - <?php echo $exception->title(); ?>


<?php echo $exception->message(); ?>


PHP <?php echo e(PHP_VERSION); ?>

Laravel <?php echo e(app()->version()); ?>

<?php echo e($exception->request()->httpHost()); ?>


## Stack Trace

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__currentLoopData = $exception->frames();
$__env->addLoop($__currentLoopData);
foreach ($__currentLoopData as $index => $frame) {
    $__env->incrementLoopIndices();
    $loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>
<?php echo e($index); ?> - <?php echo e($frame->file()); ?>:<?php echo e($frame->line()); ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
$loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php } ?><?php if ($exception->previousExceptions()->isNotEmpty()) { ?>
## Previous <?php echo e(Str::plural('exception', $exception->previousExceptions()->count())); ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__currentLoopData = $exception->previousExceptions();
    $__env->addLoop($__currentLoopData);
    foreach ($__currentLoopData as $index => $previous) {
        $__env->incrementLoopIndices();
        $loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>

### <?php echo e($index + 1); ?>. <?php echo e($previous->class()); ?>


<?php echo $previous->message(); ?>


<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__currentLoopData = $previous->frames();
        $__env->addLoop($__currentLoopData);
        foreach ($__currentLoopData as $index => $frame) {
            $__env->incrementLoopIndices();
            $loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>
<?php echo e($index); ?> - <?php echo e($frame->file()); ?>:<?php echo e($frame->line()); ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
        $loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>
<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
    $loop = $__env->getLastLoop(); ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>
<?php } ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php } ?>

## Request

<?php echo e($exception->request()->method()); ?> <?php echo e(Str::start($exception->request()->path(), '/')); ?>


## Headers

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__empty_1 = true;
$__currentLoopData = $exception->requestHeaders();
$__env->addLoop($__currentLoopData);
foreach ($__currentLoopData as $key => $value) {
    $__env->incrementLoopIndices();
    $loop = $__env->getLastLoop();
    $__empty_1 = false; ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>
* **<?php echo e($key); ?>**: <?php echo $value; ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
$loop = $__env->getLastLoop();
if ($__empty_1) { ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>
No header data available.
<?php } ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php } ?>

## Route Context

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__empty_1 = true;
$__currentLoopData = $exception->applicationRouteContext();
$__env->addLoop($__currentLoopData);
foreach ($__currentLoopData as $name => $value) {
    $__env->incrementLoopIndices();
    $loop = $__env->getLastLoop();
    $__empty_1 = false; ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>
<?php echo e($name); ?>: <?php echo $value; ?>

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
$loop = $__env->getLastLoop();
if ($__empty_1) { ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>
No routing data available.
<?php } ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php } ?>

## Route Parameters

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php } ?><?php if ($routeParametersContext = $exception->applicationRouteParametersContext()) { ?>
<?php echo $routeParametersContext; ?>

<?php } else { ?>
No route parameter data available.
<?php } ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php } ?>

## Database Queries

<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if BLOCK]><![endif]--><?php SupportCompiledWireKeys::openLoop(); ?><?php } ?><?php $__empty_1 = true;
$__currentLoopData = $exception->applicationQueries();
$__env->addLoop($__currentLoopData);
foreach ($__currentLoopData as ['connectionName' => $connectionName, 'sql' => $sql, 'time' => $time]) {
    $__env->incrementLoopIndices();
    $loop = $__env->getLastLoop();
    $__empty_1 = false; ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::startLoopIteration(); ?><?php } ?>
* <?php echo e($connectionName); ?> - <?php echo $sql; ?> (<?php echo e($time); ?> ms)
<?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::endLoop(); ?><?php } ?><?php } $__env->popLoop();
$loop = $__env->getLastLoop();
if ($__empty_1) { ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><?php SupportCompiledWireKeys::closeLoop(); ?><?php } ?>
No database queries detected.
<?php } ?><?php if (ExtendBlade::isRenderingLivewireComponent()) { ?><!--[if ENDBLOCK]><![endif]--><?php } ?>
<?php /**PATH /var/www/vendor/laravel/framework/src/Illuminate/Foundation/Providers/../resources/exceptions/renderer/markdown.blade.php ENDPATH**/ ?>