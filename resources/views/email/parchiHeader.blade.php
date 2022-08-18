<div class="container-fluid">
    <div class="card">

        <div class="card-header m-5">
            <h4>
                <span class="float-left">
                    {{@$electionSector->sector}}
                </span>
                <span class="float-right">

                {{@$electionSector->block_code}}
                </span>
            </h4>

        </div>
        <div class="row m-5">
    <span>
        {{@$electionSector->male_vote}}
    </span>
        </div>

        <div class="row m-5">
        <span>
            {{@$electionSector->female_vote}}
    </span>
        </div>
    </div>

</div>
<div class="pagebreak"> </div>
