<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueTranslationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;
    protected $table;
    protected $trans_table;
    protected $foreign;
    protected $transfield;
    protected $field;
    protected $locale;

    public function __construct($request, $table, $field, $id = null)
    {
        $this->id = $id;
        $this->request = $request;
        $this->table = $table;
        $singular = Str::singular($table);
        $this->trans_table = $singular."_translations";
        $this->foreign = $singular."_id";
        $this->field = $field;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $transfield = explode('_', $attribute)[0]; //name_ar_localized name
        $locale = explode('_', $attribute)[1];
        $query = DB::table($this->table)->join($this->trans_table,"$this->table.id","=","$this->trans_table.$this->foreign")
                 ->where("$this->table.$this->field", $this->request[$this->field])
                 ->where("$this->table.deleted_at", null)
                 ->where("$this->trans_table.$transfield",$value)
                 ->where("$this->trans_table.locale",$locale);

        if($this->id){
            $count = $query->where("$this->table.id" , "!=" , $this->id)->count();
        }else{
            $count = $query->count();
        }

        return !$count;
    }
 
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique');
    }
}
