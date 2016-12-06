<?php $this->load->view('common/header', $header_footer_data) ?>

<?php foreach ($t['stats_data'] as $t_item): ?>

    <?php foreach ($t_item['stats_order'] as $o_key => $o_value): ?>

        <table class="table ranking-default ranking-new player">

            <?php

            $caption = '';
            $end_th = '';
            if (isset($t['order_names'][$o_key]) && $t['order_names'][$o_key]) {
                $order_name = $t['order_names'][$o_key];
                $caption = $order_name . '榜';
                $end_th = '场均' . $order_name;
            } else {
                continue;
            }

            ?>

            <caption><?php echo $caption; ?></caption>

            <thead>
                <tr>
                    <th>排名</th>
                    <th>球员</th>
                    <th>球队</th>
                    <th><?php echo $end_th; ?></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($o_value as $s_index => $s_item): ?>
                    <?php

                    $extra = $s_item['extra'];

                    $player_name = $extra['player_name'];
                    if ($extra['player_short_name']) {
                        $player_name = $extra['player_short_name'];
                    }

                    $player_photo = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/hAyhodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvAD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NUVBMkM1MDhCODFGMTFFMEFDODM4N0Q0RTdBRkQ2QjMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NUVBMkM1MDlCODFGMTFFMEFDODM4N0Q0RTdBRkQ2QjMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1RUEyQzUwNkI4MUYxMUUwQUM4Mzg3RDRFN0FGRDZCMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1RUEyQzUwN0I4MUYxMUUwQUM4Mzg3RDRFN0FGRDZCMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/bAEMAAgEBAgEBAgICAgICAgIDBQMDAwMDBgQEAwUHBgcHBwYHBwgJCwkICAoIBwcKDQoKCwwMDAwHCQ4PDQwOCwwMDP/CAAsIAJYAlgEBEQD/xAAaAAEAAwEBAQAAAAAAAAAAAAAAAwQFAgEI/9oACAEBAAAAAfuYAAAAAAAs2e+K1YAC/ZCtQACzfAoVgBpTAQ5oA1ewOMoAaUwEOaALN8ChWAC/ZCtQABJpdHObGAEml0HObGAe6cgEeZ4At3QClUA91egDnK8BavABRqg0pgAhzQ91vQA8yfBNpAAZsL//xAAhEAACAQUAAgMBAAAAAAAAAAABAgMEERIgMAATECMzUP/aAAgBAQABBQL+alOW8ECjz1r4YFPj05XtDDhtNDn0p0ybeoTFuUAtHvOLx8o/z3k/PlAbx7zm0fKnfFt6h8m5wzZ7TTYdUjLlRYfLC4eMoecaZsq4jVlyEiYNxAuY09a7yJ7FIseFMnKpTgoyKjEcWGQYYnamW7c6lbNtAuMfOdco9QLt1Is2lP8Ap1nH2/H/xAAhEAACAQQCAgMAAAAAAAAAAAABMBEAITFREiAQcUBQYP/aAAgBAQAGPwL629qxWBWKtd0nPaRlk6RO1hBWPSD6WEFcbRGmQc9oGfwnJXJMKhE6ZO+4Yfkhx8//xAAkEAABAwQCAQUBAAAAAAAAAAABABEwICExQXHxURBhgZGhUP/aAAgBAQABPyH+ZlDHsLzLldWvEuEMewsSZTboVNuwsR3xiBbGI3LrwcOvH+VB+1Hw60HLrR3xiBbGI2E27FTboLMlp2baYw70OYdled33IdrW0BoWFQGjcI7WtRGEBkoLH3AFj6RjA5EO18RaXzAUAGSghDUQSltFIDkVvkg2VnILycAvU3DyUAwkIcJ+Hg0g/tTNe56//9oACAEBAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARAAQQAAAAQBAYAwAAAAAAGADCAAn//xAAnEAEAAAYBBAEEAwAAAAAAAAABABEhMDFRIEFhcYGRocHh8BBQ8f/aAAgBAQABPxD+s1KrAhZnTrG2dqcf4WNs7UoULI6dY1aJc1KrAAE3yAgSMNWiWyQp4PNggKWTzb7wre7HeFL1b/SasfpNW+8K3qx3hS92yRpYPNggaeTzb3KJAAkjyAiTcN2q3JapZdBCINhly8EAbTJkiWqWHQ3BRoKrRAUwOSUxIVOqqtloCppIIAmrlbbCF0crTAFSSSWc4dvubWMO32NjIUyIwAGRawAGTGQpk85g4Ejy/i5IHAk+T8c+4q/vH0udxU/WfpyYvJHzAEFAxcBhqNGGbyx8cRnuhl8XilOkfFP5/9k=';

                    if (isset($extra['player_photo']) && $extra['player_photo']) {
                        $player_photo = getImageUrl($extra['player_photo']);
                    }

                    $team_name = $extra['team_name'];
                    if ($extra['team_short_name']) {
                        $team_name = $extra['team_short_name'];
                    }

                    $team_barge = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/hAyhodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvAD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NUVBMkM1MDhCODFGMTFFMEFDODM4N0Q0RTdBRkQ2QjMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NUVBMkM1MDlCODFGMTFFMEFDODM4N0Q0RTdBRkQ2QjMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1RUEyQzUwNkI4MUYxMUUwQUM4Mzg3RDRFN0FGRDZCMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1RUEyQzUwN0I4MUYxMUUwQUM4Mzg3RDRFN0FGRDZCMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/bAEMAAgEBAgEBAgICAgICAgIDBQMDAwMDBgQEAwUHBgcHBwYHBwgJCwkICAoIBwcKDQoKCwwMDAwHCQ4PDQwOCwwMDP/CAAsIAJYAlgEBEQD/xAAaAAEAAwEBAQAAAAAAAAAAAAAAAwQFAgEI/9oACAEBAAAAAfuYAAAAAAAs2e+K1YAC/ZCtQACzfAoVgBpTAQ5oA1ewOMoAaUwEOaALN8ChWAC/ZCtQABJpdHObGAEml0HObGAe6cgEeZ4At3QClUA91egDnK8BavABRqg0pgAhzQ91vQA8yfBNpAAZsL//xAAhEAACAQUAAgMBAAAAAAAAAAABAgMEERIgMAATECMzUP/aAAgBAQABBQL+alOW8ECjz1r4YFPj05XtDDhtNDn0p0ybeoTFuUAtHvOLx8o/z3k/PlAbx7zm0fKnfFt6h8m5wzZ7TTYdUjLlRYfLC4eMoecaZsq4jVlyEiYNxAuY09a7yJ7FIseFMnKpTgoyKjEcWGQYYnamW7c6lbNtAuMfOdco9QLt1Is2lP8Ap1nH2/H/xAAhEAACAQQCAgMAAAAAAAAAAAABMBEAITFREiAQcUBQYP/aAAgBAQAGPwL629qxWBWKtd0nPaRlk6RO1hBWPSD6WEFcbRGmQc9oGfwnJXJMKhE6ZO+4Yfkhx8//xAAkEAABAwQCAQUBAAAAAAAAAAABABEwICExQXHxURBhgZGhUP/aAAgBAQABPyH+ZlDHsLzLldWvEuEMewsSZTboVNuwsR3xiBbGI3LrwcOvH+VB+1Hw60HLrR3xiBbGI2E27FTboLMlp2baYw70OYdled33IdrW0BoWFQGjcI7WtRGEBkoLH3AFj6RjA5EO18RaXzAUAGSghDUQSltFIDkVvkg2VnILycAvU3DyUAwkIcJ+Hg0g/tTNe56//9oACAEBAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARAAQQAAAAQBAYAwAAAAAAGADCAAn//xAAnEAEAAAYBBAEEAwAAAAAAAAABABEhMDFRIEFhcYGRocHh8BBQ8f/aAAgBAQABPxD+s1KrAhZnTrG2dqcf4WNs7UoULI6dY1aJc1KrAAE3yAgSMNWiWyQp4PNggKWTzb7wre7HeFL1b/SasfpNW+8K3qx3hS92yRpYPNggaeTzb3KJAAkjyAiTcN2q3JapZdBCINhly8EAbTJkiWqWHQ3BRoKrRAUwOSUxIVOqqtloCppIIAmrlbbCF0crTAFSSSWc4dvubWMO32NjIUyIwAGRawAGTGQpk85g4Ejy/i5IHAk+T8c+4q/vH0udxU/WfpyYvJHzAEFAxcBhqNGGbyx8cRnuhl8XilOkfFP5/9k=';

                    if (isset($extra['team_badge']) && $extra['team_badge']) {
                        $team_barge = getImageUrl($extra['team_badge']);
                    }

                    $end_td = $s_item[$o_key];
                    if ($end_td==0.0) $end_td = 0;

                    $num_class = '';
                    if ($s_index < 3) {
                        $num_class = 'text-red';
                    }

                    ?>
                    <tr>
                        <td class="num <?php echo $num_class; ?>"><?php echo($s_index + 1); ?></td>

                        <td>
                            <?php echo $player_name; ?>
                        </td>

                        <td>
                            <img class="barge" src="<?php echo $team_barge; ?>"
                                 alt="">
                            <span><?php echo $team_name; ?></span>
                        </td>

                        <td><?php echo $end_td; ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endforeach; ?>

<?php endforeach; ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
