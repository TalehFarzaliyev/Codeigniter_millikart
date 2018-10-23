# Codeigniter_millikart
Millikart ödəniş sisteminin Codeigniter Framework ilə istifadəsi.

Autoload üçün
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('millikart');
    }


Controllerdə bu şəkildə çağırıb sorğunuzu göndərə bilərsiniz

      $this->millikart->set($amount, $uniq_id, $description);
      $response = $this->millikart->getURL();
      redirect($response, 'refresh');
      
Callback üçün isə

      $reference = $this->input->get('reference');
    	if(!empty($reference) and $reference != null){
    		$callback = $this->millikart->callback($reference);
    		if($callback->RC ='000' && $callback->code == '0')
    		{
    			echo 'Succes';
    		}
    		else
    		{
    		    echo 'UnSucces';
    		}
    	}

