<?php
namespace app\command\controller;

use app\api\controller\v1\QueryLists;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Loader;

class TypeList extends Command
{
    protected function configure()
    {

        //设置参数
        $this->addArgument('go_function', Argument::REQUIRED); //必传参数

        $this->setName('TypeList')->setDescription('Here is the remark ');
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

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * User: 任亚飞
     */
    public function index()
    {
        $QueryLists = new QueryLists();
        $QueryLists->forTypeList();
    }
}
