<table class="table table-striped table-hover text-center">
    <thead>
    <tr>
        <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">#</th>

        <?php foreach ($criterios as $criterio): ?>
            
        <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">
            <?php echo $criterio['nome']; ?>
        </th>
            
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
    <?php for($i = 0; $i < count($alunos); $i++): ?>
        <tr>
            <?php echo "<th scope='row' class='nome'> [$i] {$alunos[$i]['nome']} </th>";?>

            <?php for ($j = 0; $j < count($criterios); $j++)
                echo "<td class='align-self-center align-middle'> <input type='checkbox' class='checkbox{$i}' value='{$criterios[$j]['id']}' style='width:20px;height:20px;'> </td>"; ?>
        </tr>
    <?php endfor; ?>
    </tbody>
</table>