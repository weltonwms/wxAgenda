<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disciplina;
use App\Helpers\TelegramHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\AberturaAulaReview;

class ReviewInfo extends Model
{
	protected $appends = ['tipo_review_name'];
	private $disciplina;
	private $student;

	public function celula()
	{
		return $this->belongsTo(Celula::class);
	}

	public function getTipoReviewNameAttribute()
    {        
        return $this->tipo_review==1?'Revisão de Aula/Matéria':"Tema Particular";
       
    }

	public function setAll($request,$student=null)
	{
		$this->disciplina = Disciplina::find($request->disciplina_id);
		$this->celula_id = $request->celula_id;
		$this->tipo_review = $request->tipo_review;
		$this->descricao_review = $request->descricao_review;
		if($student){
			$this->student = $student;
		}
	}
	private function validate()
	{
		if (!$this->celula_id) {
			throw new \Exception('O campo identificador da Célula é obrigatório!', 422);
		}
		if (!$this->tipo_review) {
			throw new \Exception('O campo tipo review é obrigatório!', 422);
		}
		if (!$this->descricao_review) {
			throw new \Exception('O campo descrição da review é obrigatório!', 422);
		}
		if (strlen($this->descricao_review) <= 10) {
			throw new \Exception('A descrição da review deve ter mais de 10 caracteres!', 422);
		}

	}

	public function verify()
	{
		if ($this->disciplina->review) {
			$this->validate();
		}
	}

	public function verifyAndSave()
	{
		if ($this->disciplina->review) {
			$this->validate();
			$this->save();
			$this->onAberturaCelulaReview();//notificar professor
			
		}

	}

	private function onAberturaCelulaReview()
	{
		//TelegramHelper::notificarAberturaAulaReview($this->celula, $this->student);        
        Mail::send(new AberturaAulaReview($this->celula, $this->student) );
        //Implementar outras notificações se Necessário: WhatsApp
	}

	public static function deleteByCelula($celula_id)
	{
		ReviewInfo::where('celula_id', $celula_id)->delete();
	}

}
