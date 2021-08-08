<?php 
namespace resources\views;

use app\Response\Response;

class UserTextListView extends View
{
    public function print(Response $response)
    {
        //массив
        $userThemes = $response->getKeyFeedback('userThemesArray');
        $count      = count($userThemes);
        
        if(! $count) {
            echo '<p>У вас ещё нет текстов.</p>';
        } else {
            $result = '';
            foreach($userThemes as $themeModel){
                $collection = $themeModel->getUserTextCollection();
                $count      = $collection->getTotal();

                $result .= "<ul class='user-text-list'>
                                <h4 class='user-text-list__head bright-blue-neon'>".$themeModel->getName(). " [$count]</h4>
                                <div class='select__wrapper blue-neon-box'>
                                    <span class='select__arrow'>&#9660;</span>
                                    <select class='select js_select'>";
                

                foreach ($collection as $textModel) {
                    $id        = $textModel->getId();
                    $themeName = $themeModel->getName();
                    $name      = $textModel->getName();
    
                    $result .= "<option class='user-text-list__name select__option blue-neon js_user-text-name' data-id=".$id." data-area='".$themeName."' name='".$name."'>" . $name . "</option>";
                }
                
                $result .= "</select></div></ul>";
            }

            $id      = $response->getKeyFeedback('textId');
            $message = $response->getKeyFeedback('message');
            if ($id && $message) {
                //Если есть и id и сообщение
                $result = $id. $message. $result;
            } else if ($message) {
                //При удалении id нет
                $result = $message. $result;
            }

            echo $result;
        }
    }
}
