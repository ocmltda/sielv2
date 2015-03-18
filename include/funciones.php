<?php
function paginar($regXpag, $pagActual, $paramVars, $totReg)
{
    global $t;
    $numPaginas = ceil($totReg/$regXpag);

    for ($i = 1; $i <= $numPaginas; $i++) {
        if ($i == $pagActual) {
            $listaPag .= "<B>$i</B>&nbsp; ";
        }
        else {
            if ($paramVars)
                $listaPag .= "<A HREF=\"$PHP_SELF?pag=$i&$paramVars\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">$i</A>&nbsp; ";
            else
                $listaPag .= "<A HREF=\"$PHP_SELF?pag=$i\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">$i</A>&nbsp; ";
        }
    }
    if ($pagActual > 1)
    {
        $i = $pagActual - 1;
        if ($paramVars)
            $t->assign("_ROOT.previous", "<A HREF=\"$PHP_SELF?pag=$i&$paramVars\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">Anterior</A>");
        else
            $t->assign("_ROOT.previous", "<A HREF=\"$PHP_SELF?pag=$i\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">Anterior</A>");
    }
    $t->assign("_ROOT.pages", $listaPag);

    if ($pagActual<$numPaginas)
    {
        $i = $pagActual + 1;
        if ($paramVars)
            $t->assign("_ROOT.next", "<A HREF=\"$PHP_SELF?pag=$i&$paramVars\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">Siguiente</A>");
        else
            $t->assign("_ROOT.next", "<A HREF=\"$PHP_SELF?pag=$i\" onMouseOver=\"window.status='P&aacute;gina $i';return true\" onMouseOut=\"window.status='';return true\">Siguiente</A>");
    }
}
?>