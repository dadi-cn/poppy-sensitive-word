<?php

namespace Poppy\SensitiveWord\Http\Forms\Backend;

use Poppy\Framework\Classes\Resp;
use Poppy\Framework\Exceptions\ApplicationException;
use Poppy\SensitiveWord\Action\Word;
use Poppy\SensitiveWord\Models\SysSensitiveWord;
use Poppy\System\Classes\Widgets\FormWidget;

class FormSensWordEstablish extends FormWidget
{

    public $ajax = true;

    private $id;

    /**
     * @var SysSensitiveWord
     */
    private $item;

    /**
     * 设置id
     * @param $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;

        if ($id) {
            $this->item = SysSensitiveWord::find($this->id);
            if (!$this->item) {
                throw new ApplicationException('无敏感词信息');
            }
        }
        return $this;
    }


    public function handle()
    {
        $Word = new Word();
        $Word->setPam(request()->user());
        if (is_post()) {
            if (!$Word->establish(input(), input('id'))) {
                return Resp::error($Word->getError());
            }
            return Resp::success('操作成功', '_top_reload|1');
        }

    }

    public function data(): array
    {
        if ($this->item) {
            return [
                'id'   => $this->item->id,
                'word' => $this->item->word,
            ];
        }
        return [];
    }

    public function form()
    {
        if ($this->id) {
            $this->hidden('id', 'ID');
        }

        $this->textarea('word', '敏感词')->help('逗号分隔,一行一个');
    }
}
