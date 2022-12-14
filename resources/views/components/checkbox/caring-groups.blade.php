<div id="checkboxGroups">
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
    <button class="btn btn-primary btn-sm" onclick="check_all('groupsname')" value="all"> 所有學員 </button>
    &nbsp;&nbsp;
    <input type="checkbox" name="groupsname" onclick="" value="-1"> 未分組 </input>
    &nbsp;&nbsp;
</div>

<script>
     (function() {
        axios({
            method: 'get',
            url: '/semi-api/getBatchGroups',
            params: {
                camp_id: {{ request()->route('camp_id') }},
                batch_id: {{ request()->input('batch') ?? $batches->first()->id }},
            },
            responseType: 'json'
        })
        .then(function (response) {
            if (Object.keys(response.data).length === 0) {
                console.log(response.data);
                {{-- 特殊處理 --}}
            }
            else {
                let groups = Object.entries(response.data);
                let div = document.getElementById('checkboxGroups');
                for (let i = 0; i < groups.length; i++) {
                    let input = document.createElement('input');
                    input.type = 'checkbox';
                    input.name = 'groupsname';
                    input.value = groups[i][1]['id'];
                    let label = document.createElement('label');
                    label.textContent = '\u00a0' + groups[i][1]['alias'] + '\u00a0\u00a0\u00a0';
                    div.appendChild(input);
                    div.appendChild(label);
                }
                let button = document.createElement('button');
                button.type = 'submit';
                button.className = 'btn btn-secondary btn-sm';
                button.textContent = '選定';
                div.appendChild(button);
            }
        });
    })();

    function check_all(cname) {
        let checkboxes = document.getElementsByName(cname);
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
    }
</script>