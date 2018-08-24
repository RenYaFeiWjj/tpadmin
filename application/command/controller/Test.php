<?php
namespace app\command\controller;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Loader;

class Test extends Command
{
    protected function configure()
    {

        //设置参数
        $this->addArgument('go_function', Argument::REQUIRED); //必传参数

        $this->setName('test')->setDescription('Here is the remark ');
    }

    protected function execute(Input $input, Output $output)
    {
        $result = $input->getArguments();
        switch ($result['go_function']) {
            case 'index':
                $this->index();
                break;
            default:
                # code...
                break;
        }
        // $output->writeln("TestCommand:");
    }
    public function index()
    {
        $CardConfig    = Loader::model('app\common\model\CardConfig');
        $CardConfigRes = $CardConfig->find();
        var_dump($CardConfigRes);die;
    }
}
