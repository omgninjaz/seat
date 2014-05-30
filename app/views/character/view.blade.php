@extends('layouts.masterLayout')

@section('html_title', 'View Character')

@section('page_content')

{{-- open a empty form to get a crsf token --}}
{{ Form::open(array()) }} {{ Form::close() }}

<div class="row">
    <div class="col-md-12">
        <!-- Default box -->
        <div class="box box-info">
            <div class="box-header">
                <h2 class="box-title"><i class="fa fa-user"></i> Character Details for {{ $character->characterName }}</h2>
            </div>
            <div class="box-body">
            	<div class="row">
            		<div class="col-md-2">
		                <img src='//image.eveonline.com/Character/{{ $character->characterID }}_256.jpg' class='img-circle'>
		            </div>
		            <div class="col-md-2">
                        <h3>Character Overview</h3>
                    	<dl>
                            <dt>Name</dt>
                            <dd>{{ $character->characterName }}</dd>

                            <dt>Corporation</dt>
                            <dd>{{ $character->corporationName }}</dd>

                            <dt>Race, Bloodline, Sex</dt>
                            <dd>{{ $character->race }}, {{ $character->bloodLine }}, {{ $character->gender }}</dd>

                            <dt>Date of Birth</dt>
                            <dd>{{ $character->DoB }} ({{ Carbon\Carbon::parse($character->DoB)->diffForHumans() }})</dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <h3>Other Characters on Key</h3>
                    	@if (count($other_characters) > 0)
			                @foreach ($other_characters as $alt)
				                <div class="row">
				               		<a href="{{ action('CharacterController@getView', array('characterID' => $alt->characterID )) }}" style="color:inherit;">
				                		<div class="col-md-2">
									<img src="//image.eveonline.com/Character/{{ $alt->characterID }}_64.jpg" class="img-circle">
								</div>
								<div class="col-md-5">
									<ul class="list-unstyled">
										<li><b>Name: </b>{{ $alt->characterName }}</li>
										<li><b>Corp: </b>{{ $alt->corporationName }}</li>
										<li>
											@if (strlen($alt->trainingEndTime) > 0)
												<b>Training Ends: </b> {{ Carbon\Carbon::parse($alt->trainingEndTime)->diffForHumans() }}
											@endif
										</li>
									</ul>
								</div>
								</a>
								</div><!-- ./row -->
			                @endforeach
			            @else
			            	No other known characters on this key.
			           	@endif
                    </div> <!-- ./col-md-4 -->
                    <div class="col-md-4">
                        <h3>Characters from this Person Group</h3>
                    	@if (count($people) > 0)
                    		<div class="row">
	                    		@foreach (array_chunk($people, (count($people) / 2) > 1 ? count($people) / 2 : 2) as $other_alts)
	                    			<div class="col-md-6">
			                    		<ul class="list-unstyled">
							                @foreach ($other_alts as $person)
							                	<li>
													<a href="{{ action('CharacterController@getView', array('characterID' => $person->characterID)) }}">
														<img src='//image.eveonline.com/Character/{{ $person->characterID }}_32.jpg' class='img-circle' style='width: 18px;height: 18px;'>
														{{ $person->characterName }}
													</a>				                	
												</li>
							                @endforeach
							            </ul>
							        </div>
						        @endforeach
						    </div> <!-- ./row -->
			            @else
			            	No other characters in a person group
			           	@endif
                    </div> <!-- ./col-md-4 -->
		        </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>

{{-- details such as character sheet, skills, mail, wallet etc etc here --}}
<div class="row">
	<div class="col-md-12">
	    <!-- Custom Tabs -->
	    <div class="nav-tabs-custom">
	        <ul class="nav nav-tabs">
	            <li class="active"><a href="#character_sheet" data-toggle="tab">Character Sheet</a></li>
	            <li><a href="#character_skills" data-toggle="tab">Character Skills</a></li>
	            <li id="journal_graph"><a href="#wallet_journal" data-toggle="tab">Wallet Journal</a></li>
	            <li><a href="#wallet_transactions" data-toggle="tab">Wallet Transactions</a></li>
	            <li><a href="#mail" data-toggle="tab">Mail</a></li>
	            <li><a href="#notifications" data-toggle="tab">Notifications</a></li>
	            <li><a href="#assets" data-toggle="tab">Assets</a></li>
	            <li><a href="#contacts" data-toggle="tab">Contacts</a></li>
	            <li><a href="#contracts" data-toggle="tab">Contracts</a></li>
	            <li><a href="#market_orders" data-toggle="tab">Market Orders</a></li>
	            <li class="pull-right">
	            	<a href="{{ action('ApiKeyController@getDetail', array('keyID' => $character->keyID)) }}" class="text-muted" data-toggle="tooltip" title="" data-placement="top" data-original-title="API Key Details">
	            		<i class="fa fa-gear"></i>
	            	</a>
	            </li>
	        </ul>
	        <div class="tab-content">
	        	{{-- character sheet --}}
	            <div class="tab-pane active" id="character_sheet">

	            	<div class="row">

	            		<div class="col-md-6">


			            	{{-- character information --}}
		                    <div class="box box-solid box-primary">
		                        <div class="box-header">
		                            <h3 class="box-title">Character Information</h3>
		                            <div class="box-tools pull-right">
		                            </div>
		                        </div>
		                        <div class="box-body">
		                        	<dl>
		                                <dt>Skillpoints</dt>
		                                <dd>{{ number_format($skillpoints, 0, '.', ' ') }}</dd>

		                                <dt>Clone Grade</dt>
		                                <dd>
		                                	{{ $character->cloneName }} covering {{ number_format($character->cloneSkillPoints, 0, '.', ' ') }} skillpoints
		                                	@if ($skillpoints > $character->cloneSkillPoints)
		                                		<span class="text-red"><i class="fa fa-exclamation"></i> Clone out of date</span>
		                                	@else
		                                		<span class="text-green"><i class="fa fa-check"></i> Clone OK</span>
		                                	@endif
		                                </dd>

		                                <dt>Wallet Balance</dt>
		                                <dd>{{ number_format($character->balance, 2, '.', ' ') }} ISK</dd>
		                            </dl>
		                        </div><!-- /.box-body -->
		                    </div><!-- /.box -->

			            	{{-- skill information --}}
		                    <div class="box box-solid box-primary">
		                        <div class="box-header">
		                            <h3 class="box-title">Skills Information</h3>
		                            <div class="box-tools pull-right">
		                            </div>
		                        </div>
		                        <div class="box-body">
		                        	<dl>
		                                <dt>Currently Training</dt>
		                                <dd>
		                                	@if ( strlen($character->typeName) > 0)
			                                	{{ $character->typeName }} {{ $character->trainingToLevel }}
			                                @else
			                                	<span class="text-yellow"><i class="fa fa-exclamation"></i> This character is not currently training</span>
			                                @endif
		                                </dd>

		                                <dt>Training Ends</dt>
		                                <dd>
		                                	{{ $character->trainingEndTime }} ({{ Carbon\Carbon::parse($character->trainingEndTime)->diffForHumans() }})
		                                	@if (Carbon\Carbon::parse($character->trainingEndTime)->lte(Carbon\Carbon::now()->addDay()))
		                                		<span class="text-yellow"><i class="fa fa-exclamation"></i> Less than 24hrs worth of training left</span>
		                                	@else
		                                		<span class="text-green"><i class="fa fa-check"></i> More than 24hrs worth of training left</span>
		                                	@endif
		                                </dd>

		                                <dt>Skill Queue</dt>
		                                <dd>
		                                	<ol>
		                                		@foreach($skill_queue as $skill)
		                                			<li>{{ $skill->typeName }} {{ $skill->level }}</li>
		                                		@endforeach
		                                	</ol>
		                                </dd>

		                                <dt>Skill Queue Ends</dt>
		                                <dd>
		                                	{{ end($skill_queue)->endTime }} ({{ Carbon\Carbon::parse(end($skill_queue)->endTime)->diffForHumans() }})
		                                	@if (Carbon\Carbon::parse(end($skill_queue)->endTime)->lte(Carbon\Carbon::now()->addDay()))
		                                		<span class="text-yellow"><i class="fa fa-exclamation"></i> Less than 24hrs worth of skill queue left</span>
		                                	@else
		                                		<span class="text-green"><i class="fa fa-check"></i> More than 24hrs worth of skill queue left</span>
		                                	@endif
		                                </dd>
		                            </dl>
		                        </div><!-- /.box-body -->
		                    </div><!-- /.box -->



			            	{{-- key/account information --}}
		                    <div class="box box-solid box-primary">
		                        <div class="box-header">
		                            <h3 class="box-title">Key/Account Information</h3>
		                            <div class="box-tools pull-right">
		                            </div>
		                        </div>
		                        <div class="box-body">
		                        	<dl>
		                                <dt>Key ID</dt>
		                                <dd>{{ $character->keyID }}</dd>

		                                <dt>Key Type</dt>
		                                <dd>{{ $character->type }}</dd>

		                                <dt>Access Mask</dt>
		                                <dd>{{ $character->accessMask }}</dd>

		                                <dt>Paid Until</dt>
		                                <dd>{{ $character->paidUntil }} (Payment due {{ Carbon\Carbon::parse($character->paidUntil)->diffForHumans() }})</dd>

		                                <dt>Logon Count</dt>
		                                <dd>{{ $character->logonCount }} logins to eve related services</dd>

		                                <dt>Online Time</dt>
		                                <dd>{{ $character->logonMinutes }} minutes, {{ round(((int)$character->logonMinutes/60),0) }} hours or {{ round(((int)$character->logonMinutes/60)/24,0) }} days</dd>
		                            </dl>
		                        </div><!-- /.box-body -->
		                    </div><!-- /.box -->

			            	{{-- augmentation information --}}
		                    <div class="box box-solid box-primary">
		                        <div class="box-header">
		                            <h3 class="box-title">Augmentations Information</h3>
		                            <div class="box-tools pull-right">
		                            </div>
		                        </div>
		                        	<div class="box-body">
										<dl>
											<dt>Intelligence</dt>
											<dd>@if(!$character->intelligenceAugmentatorValue)
													<span class="text-orange"><i class="fa fa-exclamation"></i> {{ $character->intelligenceAugmentatorValue + $character->intelligence }}</span>
												@else
													<span class="text-green"><i class="fa fa-check"></i> {{ $character->intelligenceAugmentatorValue + $character->intelligence }}</span>
												@endif
												{{ $character->intelligenceAugmentatorName }} (17 Base + {{ $character->intelligenceAugmentatorValue ? $character->intelligenceAugmentatorValue : 0}} implant + {{ $character->intelligence -17 }} remap)</dd>

											<dt>Memory</dt>
											<dd>@if(!$character->memoryAugmentatorValue)
													<span class="text-orange"><i class="fa fa-exclamation"></i> {{ $character->memoryAugmentatorValue + $character->memory }}</span>
												@else
													<span class="text-green"><i class="fa fa-check"></i> {{ $character->memoryAugmentatorValue + $character->memory }}</span>
												@endif
												{{ $character->memoryAugmentatorName }} (17 Base + {{ $character->memoryAugmentatorValue ? $character->memoryAugmentatorValue : 0}} implant + {{ $character->memory -17 }} remap)</dd>

											<dt>Perception</dt>
											<dd>@if(!$character->perceptionAugmentatorValue)
													<span class="text-orange"><i class="fa fa-exclamation"></i> {{ $character->perceptionAugmentatorValue + $character->perception }}</span>
												@else
													<span class="text-green"><i class="fa fa-check"></i> {{ $character->perceptionAugmentatorValue + $character->perception }}</span>
												@endif
												{{ $character->perceptionAugmentatorName }} (17 Base + {{ $character->perceptionAugmentatorValue ? $character->perceptionAugmentatorValue : 0}} implant + {{ $character->perception -17 }} remap)</dd>

											<dt>Willpower</dt>
											<dd>@if(!$character->willpowerAugmentatorValue)
													<span class="text-orange"><i class="fa fa-exclamation"></i> {{ $character->willpowerAugmentatorValue + $character->willpower }}</span>
												@else
													<span class="text-green"><i class="fa fa-check"></i> {{ $character->willpowerAugmentatorValue + $character->willpower }}</span>
												@endif
												{{ $character->willpowerAugmentatorName }} (17 Base + {{ $character->willpowerAugmentatorValue ? $character->willpowerAugmentatorValue : 0}} implant + {{ $character->willpower -17 }} remap)</dd>

											<dt>Charisma</dt>
											<dd>@if(!$character->charismaAugmentatorValue)
													<span class="text-orange"><i class="fa fa-exclamation"></i> {{ $character->charismaAugmentatorValue + $character->charisma }}</span>
												@else
													<span class="text-green"><i class="fa fa-check"></i> {{ $character->charismaAugmentatorValue + $character->charisma }}</span>
												@endif
												{{ $character->charismaAugmentatorName }} (17 Base + {{ $character->charismaAugmentatorValue ? $character->charismaAugmentatorValue : 0 }} implant + {{ $character->charisma -17 }} remap)</dd>
										</dl>
									</div><!-- /.box-body -->
		                    </div><!-- /.box -->

		                </div> <!-- ./col-md-6 -->

	            		<div class="col-md-6">

	            			{{-- if we have the character info from eve_characterinfo, dispaly that --}}
	            			@if (!empty($character_info))

				            	{{-- employment information --}}
			                    <div class="box box-solid box-primary">
			                        <div class="box-header">
			                            <h3 class="box-title">Employment History ({{ count($employment_history) }})</h3>
			                            <div class="box-tools pull-right">
			                            </div>
			                        </div>
			                        <div class="box-body">
			                        	<ul class="list-unstyled">
			                        		@foreach($employment_history as $employment)
			                        			<li>
			                        				<img src='https://image.eveonline.com/Corporation/{{ $employment->corporationID }}_64.png' class='img-circle'>
			                        				Joined <b><span rel="id-to-name">{{ $employment->corporationID }}</span></b> on {{ $employment->startDate }} ({{ Carbon\Carbon::parse($employment->startDate)->diffForHumans() }})
			                        			</li>
			                        		@endforeach
			                        	</ul>
			                        </div><!-- /.box-body -->
			                    </div><!-- /.box -->

				            	{{-- Ship & Location Information --}}
			                    <div class="box box-solid box-primary">
			                        <div class="box-header">
			                            <h3 class="box-title">Ship, Location &amp; Other</h3>
			                            <div class="box-tools pull-right">
			                            </div>
			                        </div>
			                        <div class="box-body">
			                        	<ul class="list-unstyled">
											{{-- ship type --}}
											@if (!empty($character_info->shipTypeName))
												<li><b>Ship:</b> {{ $character_info->shipTypeName }} called '{{ $character_info->shipName }}'</li>
											@endif
											{{-- last location --}}
											@if (!empty($character_info->lastKnownLocation))
												<li><b>Last Location:</b> {{ $character_info->lastKnownLocation }}</li>
											@endif
											{{-- security status --}}
											@if (!empty($character_info->securityStatus))
												<li>
													<b>Security Status:</b>
													@if ($character_info->securityStatus < -5)
														<span class="text-red">{{ $character_info->securityStatus }}</span>
													@elseif ($character_info->securityStatus < -2)
														<span class="text-yellow">{{ $character_info->securityStatus }}</span>
													@else
														<span class="text-green">{{ $character_info->securityStatus }}</span>
													@endif
												</li>
											@endif
			                        	</ul>
			                        </div><!-- /.box-body -->
			                    </div><!-- /.box -->

			                @endif

		                </div> <!-- ./col-md-6 -->

	                </div> <!-- ./row -->

	            </div><!-- /.tab-pane -->

	            {{-- character skills --}}
	            <div class="tab-pane" id="character_skills">
	            	<div class="row">
	            		<div class="col-md-6">
			            	{{--
			            		Lets try and document this for a change.
			            		
			            		We start by looping over the available groups, found in $skill_groups
			            		passed by the controller. Every pass of a new group will count the amount
			            		of skills the character has in that particulat group as $character_skills
			            		array has the groupID as a key.

			            		If a group has more than 0 skills, we prepare a 'box' and loop over the actual
			            		skills for that group, displaying the level etc.
			            	--}}
			            	@foreach ($skill_groups as $skill_group)

			            		@if ( isset($character_skills[$skill_group->groupID]) && count($character_skills[$skill_group->groupID]) > 0)
				                    <div class="box box-solid">
				                        <div class="box-header">
				                            <h3 class="box-title">{{ $skill_group->groupName }}</h3>
				                            <div class="box-tools pull-right">
				                            </div>
				                        </div>
				                        <div class="box-body">
				                        	<ul class="list-unstyled">
				                        		{{--*/$group_sp = 0;/*--}}
				                        		@foreach ($character_skills[$skill_group->groupID] as $skill)
			                                        <li>
			                                        	<i class="fa fa-book"></i> {{ $skill['typeName'] }}
			                                        	<span class="pull-right">
			                                        		{{--
			                                        			Here we check if the skills level is 0. If so, just display
			                                        			a empty star. Else, check if its fully trained (level 5) and display
			                                        			5 green stars.
			                                        			Lastly, if neither of the above are the case, display stars equal to the
			                                        			level of the skill.
			                                        		--}}
			                                        		@if ($skill['level'] == 0)
				                                        		<i class="fa fa-star-o"></i>
				                                        	@elseif ($skill['level'] == 5)
				                                        		<span class="text-green">
				                                        			<i class="fa fa-star"></i>
				                                        			<i class="fa fa-star"></i>
				                                        			<i class="fa fa-star"></i>
				                                        			<i class="fa fa-star"></i>
				                                        			<i class="fa fa-star"></i>
				                                        		</span>
			                                        		@else
			                                        			@for ($i=0; $i < $skill['level'] ; $i++)
			                                        				<i class="fa fa-star"></i>
			                                        			@endfor	
			                                        		@endif
			                                        		| {{ $skill['level'] }}
			                                        	</span>
			                                        </li>
			                                        {{--*/$group_sp += $skill['skillpoints'];/*--}}
		                                        @endforeach
		                                    </ul>
				                        </div><!-- /.box-body -->
				                        <div class="box-footer">
				                        	{{-- $group_sp comes from the comment hack above ;D --}}
				                        	<b>{{ number_format($group_sp) }}</b> Total Skillpoints
				                        </div>
				                    </div><!-- /.box -->
			                   @endif
			            	@endforeach
		                </div> <!-- ./col-md-6 -->
		                <div class="col-md-6">


							<div class="box box-solid">
							    <div class="box-header">
							        <h3 class="box-title">Spaceship Command Skills</h3>
							    </div>

							    <div class="box-body">
									<table class="table table-condensed table-hover" id="datatable">
									    <thead>
									    	<tr>
									        <th>Skill</th>
									        <th>Amarr</th>
									        <th>Caldari</th>
									        <th>Gallente</th>
									        <th>Minmatar</th>
										    </tr>
										</thead>
										<tbody>
										    <tr>
										        <td>Frigate</td>

										        	@foreach( array(3331, 3330, 3328 ,3329) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Destroyer</td>
										        	@foreach( array(33091, 33092, 33093 ,33094) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Cruiser</td>
										        	@foreach( array(3335, 3334, 3332 ,3333) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Battlecruiser</td>
										        	@foreach( array(33095, 33096, 33097 ,33098) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Battleship</td>
										        	@foreach( array(3339, 3338, 3336 ,3337) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Strategic Cruiser</td>
										        	@foreach( array(30650, 30651, 30652 ,30653) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Industrial</td>
										        	@foreach( array(3343, 3342, 3340 ,3341) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>									    
										    <tr>
										        <td>Freighter</td>
										        	@foreach( array(20524, 20526, 20527 ,20528) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>									    
										    <tr>
										        <td>Carrier</td>
										        	@foreach( array(24311, 24312, 24313 ,24314) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>
										    <tr>
										        <td>Dreadnaught</td>
										        	@foreach( array(20525, 20530, 20531 ,20532) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>	
										        <td>Titan</td>
										        	@foreach( array(3347, 3346, 3344 ,3345) as $s)
												        <td>
												        	@if (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 5)
											        			<span class="label label-success">5</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) == 0)
													        	<span class="label label-default">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
												        	@elseif (App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) <= 2)
													        	<span class="label label-danger">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @else
														        <span class="label label-primary">{{ App\Services\Helpers\Helpers::findSkillLevel($character_skills, $s) }}</span>
													        @endif
													    </td>
													@endforeach
										    </tr>										    
										</tbody>
									</table>
							    </div><!-- /.box-body -->
							</div>

		                </div>
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->

	            {{-- wallet journal --}}
	            <div class="tab-pane" id="wallet_journal">
	            	<div class="row">
	            		<div class="col-span-12">
	            			<div id="chart" style="height:200px;"></div>
	            		</div>
	            	</div>
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Wallet Journal ({{ count($wallet_journal) }})</h3>
                                    <div class="box-tools">
                                    	<a href="{{ action('CharacterController@getFullWalletJournal', array('characterID' => $character->characterID)) }}" class="btn btn-default btn-sm pull-right">
                                    		<i class="fa fa-money"></i> View Full Journal
                                    	</a>
                                    </div>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-condensed table-hover" id="datatable">
                                        <thead>
	                                        <tr>
	                                            <th>Date</th>
	                                            <th>Type</th>
	                                            <th>Owner1 Name</th>
	                                            <th>Owner2 Name</th>
	                                            <th>ArgName 1</th>
	                                            <th>Amount</th>
	                                            <th>Balance</th>
	                                        </tr>
                                        </thead>
                                        <tbody>
	                                        @foreach ($wallet_journal as $e)
		                                        <tr @if ($e->amount < 0)class="danger" @endif>
		                                            <td>
		                                            	<spanp data-toggle="tooltip" title="" data-original-title="{{ $e->date }}">
		                                            		{{ Carbon\Carbon::parse($e->date)->diffForHumans() }}
		                                            	</span>
		                                            </td>
		                                            <td>{{ $e->refTypeName }}</td>
		                                            <td>
		                                            	<img src='{{ App\Services\Helpers\Helpers::generateEveImage($e->ownerID1, 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
		                                            	{{ $e->ownerName1 }}
		                                            </td>
		                                            <td>
		                                            	<img src='{{ App\Services\Helpers\Helpers::generateEveImage($e->ownerID2, 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
		                                            	{{ $e->ownerName2 }}
		                                            </td>
		                                            <td>{{ $e->argName1 }}</td>
		                                            <td>{{ number_format($e->amount, 2, '.', ' ') }}</td>
		                                            <td>{{ number_format($e->balance, 2, '.', ' ') }}</td>
		                                        </tr>
		                                    @endforeach
                                    	</tbody>
                                   	</table>
                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->

	            {{-- wallet transactions --}}
	            <div class="tab-pane" id="wallet_transactions">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Wallet Transactions ({{ count($wallet_transactions) }})</h3>
                                    <div class="box-tools">
                                    	<a href="{{ action('CharacterController@getFullWalletTransactions', array('characterID' => $character->characterID)) }}" class="btn btn-default btn-sm pull-right">
                                    		<i class="fa fa-money"></i> View Full Transactions
                                    	</a>
                                    </div>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
					                <table class="table table-condensed table-hover" id="datatable">
					                    <thead>
					                        <tr>
												<th>Date</th>
												<th>Type</th>
												<th>#</th>
												<th>Per Item</th>
												<th>Total</th>
												<th>Client</th>
												<th>Type</th>
												<th>Station Name</th>
					                        </tr>
					                    </thead>
					                    <tbody>
					                        @foreach ($wallet_transactions as $e)
					                            <tr @if ($e->transactionType == 'buy')class="danger" @endif>
					                                <td>
					                                	<spanp data-toggle="tooltip" title="" data-original-title="{{ $e->transactionDateTime }}">
					                                		{{ Carbon\Carbon::parse($e->transactionDateTime)->diffForHumans() }}
					                                	</span>
					                                </td>
					                                <td>
					                                	<img src='//image.eveonline.com/Type/{{ $e->typeID }}_32.png' style='width: 18px;height: 18px;'>
					                                	{{ $e->typeName }}
					                                </td>
					                                <td>{{ $e->quantity }}</td>
					                                <td>{{ number_format($e->price, 2, '.', ' ') }} ISK</td>
					                                <td>{{ number_format($e->price * $e->quantity, 2, '.', ' ') }} ISK</td>
					                                <td>{{ $e->clientName }}</td>
					                                <td>{{ $e->transactionType }}</td>
					                                <td>{{ $e->stationName }}</td>
					                            </tr>
					                        @endforeach
					                	</tbody>
					               	</table>
                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->

	            {{-- character mail --}}
	            <div class="tab-pane" id="mail">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Mail ({{ count($mail) }})</h3>
                                    <div class="box-tools">
                                    	<a href="{{ action('CharacterController@getFullMail', array('characterID' => $character->characterID)) }}" class="btn btn-default btn-sm pull-right">
                                    		<i class="fa fa-envelope-o"></i> All Mail
                                    	</a>
                                    </div>                                    
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
							        <table class="table table-hover table-condensed" id="datatable">
							            <thead>
								            <tr>
								                <th style="width: 10px">#</th>
								                <th>Date</th>
								                <th>From</th>
								                <th>To</th>
								                <th>Subject</th>
								                <th></th>
								            </tr>
							            </thead>
							            <tbody>
											@foreach ($mail as $message)
									            <tr>
									                <td>{{ $message->messageID }}</td>
									                <td>
									                	<spanp data-toggle="tooltip" title="" data-original-title="{{ $message->sentDate }}">
									                		{{ Carbon\Carbon::parse($message->sentDate)->diffForHumans() }}
									                	</span>
									                </td>
									                <td>
							                    		<a href="{{ action('CharacterController@getView', array('characterID' => $message->senderID)) }}">
							                    			<img src='//image.eveonline.com/Character/{{ $message->senderID }}_32.jpg' class='img-circle' style='width: 18px;height: 18px;'>
							                    		</a>
									                	{{ $message->senderName }}
									                </td>
									                <td>
												    	@if (strlen($message->toCorpOrAllianceID) > 0)
													    	<b>{{ count(explode(',', $message->toCorpOrAllianceID)) }}</b> Corporation(s) / Alliance(s)
													    @endif
													    @if (strlen($message->toCharacterIDs) > 0)
													    	<b>{{ count(explode(',', $message->toCharacterIDs)) }}</b> Character(s)
													    @endif
													    @if (strlen($message->toListID) > 0)
													    	<b>{{ count(explode(',', $message->toListID)) }}</b> Mailing List(s)
													    @endif				                	
									                </td>
									                <td><b>{{ $message->title }}</b></td>
									                <td>
									                	{{ HTML::linkAction('MailController@getRead', 'Permalink', array('messageID' => $message->messageID ), array('class' => 'btn btn-primary btn-xs pull-right')) }}
									                </td>
									            </tr>
											@endforeach

							        	</tbody>
							        </table>
                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->

	            {{-- character notification --}}
	            <div class="tab-pane" id="notifications">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Notifications ({{ count($notifications) }})</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
							        <table class="table table-hover table-condensed" id="datatable">
							            <thead>
								            <tr>
								                <th style="width: 10px">#</th>
								                <th>Date</th>
								                <th>From</th>
								                <th>Notification Type</th>
								                <th>Sample</th>
								                <th></th>
								            </tr>
							            </thead>
							            <tbody>
											@foreach ($notifications as $note)
									            <tr>
									                <td>{{ $note->notificationID }}</td>
									                <td>
									                	<spanp data-toggle="tooltip" title="" data-original-title="{{ $note->sentDate }}">
									                		{{ Carbon\Carbon::parse($note->sentDate)->diffForHumans() }}
									                	</span>
									                </td>
									                <td>{{ $note->senderName }}</td>
									                <td><b>{{ $note->description }}</b></td>
									                <td><b>{{ str_limit($note->text, 80, $end = '...') }}</b></td>
									                <td></td>
									            </tr>
											@endforeach
							        	</tbody>
							        </table>
                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->


	            {{-- character assets --}}
				<div class="tab-pane" id="assets">
					<div class="row">
						<div class="col-md-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Assets ({{ $assets_count }})</h3>
								</div><!-- /.box-header -->
								<div class="box-body no-padding">
									@foreach ($assets_list as $location => $assets)
										<div class="box box-solid box-primary">
											<div class="box-header">
												<h3 class="box-title">{{ $location }} ({{ count($assets) }}) {{ App\Services\Helpers\Helpers::sumVolume($assets, 'volume') }} m3</h3>
												<div class="box-tools pull-right">
													<button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>	
												</div>
											</div>
											<div class="box-body no-padding">	
												<div class="row">
													@foreach (array_chunk($assets, (count($assets) / 2) > 1 ? count($assets) / 2 : 2) as $column)
														<div class="col-md-6">
															<table class="table table-hover table-condensed">
															<tbody>
																<tr>
																	<th style="width: 40px">#</th>
																	<th style="width: 50%" colspan="2">Type</th>
																	<th>Group</th>
																	<th>m<sup>3</sup></th>
																	<th style="width: 50px"></th>
																</tr>
															</tbody>
																@foreach ($column as $asset)
																	<tbody style="border-top:0px solid #FFF">
																		<tr class="item-container">
																			<td>{{ App\Services\Helpers\Helpers::formatBigNumber($asset['quantity']) }}</td>
																			<td colspan="2">
																				<span data-toggle="tooltip" title="" data-original-title="{{ number_format($asset['quantity'], 0, '.', ' ') }} x {{ $asset['typeName'] }}">
																					<img src='//image.eveonline.com/Type/{{ $asset['typeID'] }}_32.png' style='width: 18px;height: 18px;'>
																					{{ str_limit($asset['typeName'], 35, $end = '...') }} {{ isset($asset['contents']) ? "(". count($asset['contents']) . ")" : "" }}
																				</span>
																			</td>
																			<td>
																				<span data-toggle="tooltip" title="" data-original-title="{{ $asset['groupName'] }}">
																					{{ str_limit($asset['groupName'], 40, $end = '...') }}
																				</span>
																			</td>
																			<td>
																				<span data-toggle="tooltip" title="" data-original-title="{{ number_format($asset['volume'], 0, '.', ' ') }}m3">
																					{{ App\Services\Helpers\Helpers::formatBigNumber($asset['volume']) }}
																				</span>
																			</td>
																			@if(isset($asset['contents']))
																				<td style="text-align: right"><i class="fa fa-plus viewcontent" style="cursor: pointer;"></i></td>
																			@else
																				<td></td>
																			@endif
																		</tr>
																	</tbody>
																	@if(isset($asset['contents']))
																		<tbody style="border-top:0px solid #FFF" class="tbodycontent">
																			@foreach ($asset['contents'] as $content)
																				<tr class="hidding">
																					<td>{{ App\Services\Helpers\Helpers::formatBigNumber($content['quantity']) }}</td>
																					<td style="width: 18px;"></td>
																					<td>
																						<span data-toggle="tooltip" title="" data-original-title="{{ number_format($content['quantity'], 0, '.', ' ') }} x {{ $content['typeName'] }}">
																							<img src='//image.eveonline.com/Type/{{ $content['typeID'] }}_32.png' style='width: 18px;height: 18px;'>
																							{{ str_limit($content['typeName'], 30, $end = '...') }}
																						</span>
																					</td>
																					<td>
																						<span data-toggle="tooltip" title="" data-original-title="{{ $content['groupName'] }}">
																							{{ str_limit($content['groupName'], 25, $end = '...') }}
																						</span>
																					</td>
																					<td>
																						<span data-toggle="tooltip" title="" data-original-title="{{ number_format($content['volume'], 0, '.', ' ') }}m3">{{ App\Services\Helpers\Helpers::formatBigNumber($content['volume']) }}
																						</span>
																					</td>

																					<td></td>
																				</tr>
																			@endforeach
																		</tbody>
																	@endif
																@endforeach
															</table>
														</div> <!-- /.col-md-6 -->
													@endforeach 
												</div> <!-- /.row -->
											</div><!-- /.box-body -->
										</div> <!-- ./box -->
									@endforeach	
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div> <!-- ./col-md-12 -->
					</div> <!-- ./row -->
				</div><!-- /.tab-pane -->

	            {{-- character contacts --}}
	            <div class="tab-pane" id="contacts">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Contact List ({{ count($contact_list) }})</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                	<div class="row">
                                		@foreach (array_chunk($contact_list, (count($contact_list) / 6) > 1 ? count($contact_list) / 6 : 6) as $list)

                                			<div class="col-md-2">
										        <table class="table table-hover table-condensed">
										            <thead>
											            <tr>
											                <th>Name</th>
											                <th>Standing</th>
											            </tr>
											        </thead>
											        <tbody>
														@foreach ($list as $contact)
												            <tr>
												                <td>
										                    		<a href="{{ action('CharacterController@getView', array('characterID' => $contact->contactID)) }}">
										                    			<img src='//image.eveonline.com/Character/{{ $contact->contactID }}_32.jpg' class='img-circle' style='width: 18px;height: 18px;'>
										                    			{{ $contact->contactName }}
										                    		</a>
												                </td>
												                <td>
												                	@if ($contact->standing == 0)
												                		{{ $contact->standing }}
												                	@elseif ($contact->standing > 0)
													                	<span class="text-green">{{ $contact->standing }}</span>
													                @else
													                	<span class="text-red">{{ $contact->standing }}</span>
													                @endif
												                </td>
												            </tr>
														@endforeach
										        	</tbody>
										        </table>
										    </div> <!-- ./col-md-2 -->

                                		@endforeach
                                	</div><!-- ./row -->

                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->
				
	            {{-- character contracts --}}
	            <div class="tab-pane" id="contracts">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Contract List ({{ count($contracts_courier) +  count($contracts_other)}})</h3>
                                </div><!-- /.box-header -->

                                <div class="box-body no-padding">
                                	<div class="row">

										{{-- Building box for contracts like Itemexchange and auction --}}
										<div class="col-md-6">
											<div class="box box-solid box-primary">
												<div class="box-header">
													<h3 class="box-title">Item Exchange &amp; Auction ({{ count($contracts_other) }})</h3>
													<div class="box-tools pull-right">
														<button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>	
													</div>
												</div>
												<div class="box-body no-padding">	
													<div class="row">
														<div class="col-md-12">
															<table class="table table-hover table-condensed">
																<tbody>
																	<tr>
																		<th style="width: 200px">Issuer</th>
																		<th style="width: 200px">Assignee</th>
																		<th>type</th>
																		<th style="width: 30px">Status</th>
																		<th style="width: 30px"></th>
																	</tr>
																</tbody>
															</table>
															{{-- Loop over other contracts and display --}}
															@foreach ($contracts_other as $contract)
																<table class="table table-hover table-condensed">
																	<tbody style="border-top:0px solid #FFF">
																		<tr class="item-container">
																			<td style="width: 200px">
																				<img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['issuerID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
																				<span rel="id-to-name">{{ $contract['issuerID'] }}</span>
																			</td>
																			<td style="width: 200px">
																				@if ($contract['assigneeID'] <> 0)
																					<img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['assigneeID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
																					<span rel="id-to-name">{{ $contract['assigneeID'] }}</span>
																				@else
																					Unknown Assignee
																				@endif
																			</td>
																			<td>{{ $contract['type'] }}</td>
																			{{-- Check the status and display icon for this status --}}
																			<td style="width: 30px">
																				@if($contract['status'] == 'Completed')
																					<span class="text-green" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-check"></i></span>
																				@elseif($contract['status'] == 'Outstanding')
																					<span class="text-orange" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-clock-o"></i></span>
																				@else
																					<span class="text-red" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-times"></i></span>
																				@endif
																			</td>
																			<td style="text-align: right; width: 30px"><i class="fa fa-plus viewcontent contracts" style="cursor: pointer;"></i></td>
																		</tr>
																	</tbody>
																</table>
																{{-- Add additionnal information for the contracts and give a specific class (tbodycontent) for toggle it --}}
																<div class="col-md-12 tbodycontent">
																	<ul class="list-unstyled">
																		<li>
																			<i class="fa fa-map-marker"></i> 
																			<span data-toggle="tooltip" title="" data-original-title="{{ $contract['startlocation'] }}">
																				<b>{{ str_limit($contract['startlocation'], 100, $end = '...') }}</b>
																			</span>
																		</li>
																		@if(isset($contract['title']) && strlen($contract['title']) > 0)
																			<li>
																				<i class="fa fa-bullhorn" data-original-title=" {{ $contract['title'] }}" title="" data-toggle="tooltip"></i> 
																				Title: <b>{{ str_limit($contract['title'], 100, $end = '...') }}</b>
																			</li>
																		@endif
																		<li>
																			<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateIssued'] }}" title="" data-toggle="tooltip"></i> 
																			Issued: <b>{{ Carbon\Carbon::parse($contract['dateIssued'])->diffForHumans() }}</b>
																		</li>
																		{{-- If the contract is not completed, we display the expiration date else nothing --}}
																		@if(!isset($contract['dateCompleted']))
																			<li>
																				<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateExpired'] }}" title="" data-toggle="tooltip"></i> 
																				Expires: <b>{{ Carbon\Carbon::parse($contract['dateExpired'])->diffForHumans() }}</b>
																			</li>	
																		@endif
																		{{-- If the contract is completed we display the date --}}
																		@if(isset($contract['dateCompleted']))
																			<li>
																				<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateCompleted'] }}" title="" data-toggle="tooltip"></i> 
																				Completed: <b>{{ Carbon\Carbon::parse($contract['dateCompleted'])->diffForHumans() }}</b> 
																				by <img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['acceptorID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'> 
																				<b><span rel="id-to-name">{{ $contract['acceptorID'] }}</span></b>
																			</li>
																		@endif
																		<li>
																			<i class="fa fa-money"></i> 
																			Buyer will get <b><span class="text-green">{{ number_format($contract['reward'], 2, '.', ' ') }}</span></b> ISK
																		</li>
																		<li>
																			<i class="fa fa-money"></i> 
																			Buyer will pay <b><span class="text-red">{{ number_format($contract['price'], 2, '.', ' ') }}</span></b> ISK
																		</li>
																		{{-- If the contract is an auction we display the buyout price --}}
																		@if($contract['type'] == 'Auction')
																			<li>
																				<i class="fa fa-money"></i> 
																				<b>{{ number_format($contract['buyout'], 2, '.', ' ') }}</b> ISK buyout
																			</li>
																		@endif
																		{{-- Check if contract has contents --}}
																		@if(isset($contract['contents']) && count($contract['contents']) > 0)
																			<li> <i class="fa fa-paperclip"></i> Contents</li>
																			<li>
																				<div class="col-md-6">
																					<ul>
																						<li style="list-style:none;">
																						<span class="text-green"><b>Buyer will get</b></span>
																						</li>
																						{{-- Loop over contents and display item in contract --}}
																						@foreach($contract['contents'] as $content)
																							<li style="list-style:none;">
																								{{-- Check if it's a item request or not --}}
																								@if($content['included'] == 1)
																									<img src='//image.eveonline.com/Type/{{ $content['typeID'] }}_32.png' style='width: 18px;height: 18px;'> 
																									<span>{{  number_format($content['quantity'], 0, '.', ' ') }} x {{ $content['typeName'] }}</span>
																								@endif
																							</li>
																						@endforeach
																					</ul>
																				</div>
																				<div class="col-md-6">
																					<ul>
																						<li style="list-style:none;">
																						<span class="text-red"><b>Buyer will pay</b></span>
																						</li>
																						{{-- Loop over contents and display item requested in contract --}}
																						@foreach($contract['contents'] as $content)
																							<li style="list-style:none;">
																								{{-- Check if it's a item request or not --}}
																								@if($content['included'] == 0)
																									<img src='//image.eveonline.com/Type/{{ $content['typeID'] }}_32.png' style='width: 18px;height: 18px;'> 
																									<span>{{  number_format($content['quantity'], 0, '.', ' ') }} x {{ $content['typeName'] }}</span>
																								@endif
																							</li>
																						@endforeach
																					</ul>
																				</div>
																			</li>
																		@endif
																	</ul>
																</div><!-- ./col-md-12 -->
															@endforeach
														</div><!-- ./col-md-12 -->
													</div><!-- ./row -->
												</div><!-- ./box-body -->
											</div><!-- ./box -->
										</div> <!-- ./col-md-6 -->

										{{-- Building box for courier contracts --}}
										<div class="col-md-6">
											<div class="box box-solid box-primary">
												<div class="box-header">
													<h3 class="box-title">Courier ({{ count($contracts_courier) }})</h3>
													<div class="box-tools pull-right">
														<button class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>	
													</div>
												</div>
												<div class="box-body no-padding">	
													<div class="row">
														<div class="col-md-12">
															<table class="table table-condensed">
																<tbody>
																	<tr>
																		<th style="width: 200px">Issuer</th>
																		<th style="width: 200px">Assignee</th>
																		<th>type</th>
																		<th style="width: 30px">Status</th>
																		<th style="width: 30px"></th>
																	</tr>
																</tbody>
															</table>
															{{-- Loop over contracts courier and display --}}
															@foreach ($contracts_courier as $contract)
																<table class="table table-hover table-condensed">
																	<tbody style="border-top:0px solid #FFF">
																		<tr class="item-container">
																			<td style="width: 200px">
																				<img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['issuerID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
																				<span rel="id-to-name">{{ $contract['issuerID'] }}</span>
																			</td>
																			<td style="width: 200px">
																				@if ($contract['assigneeID'] <> 0)
																					<img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['assigneeID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'>
																					<span rel="id-to-name">{{ $contract['assigneeID'] }}</span>
																				@else
																					Unknown Assignee
																				@endif
																			</td>
																			<td>{{ $contract['type'] }}</td>
																			<td style="width: 30px">
																				@if($contract['status'] == 'Completed')
																					<span class="text-green" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-check"></i></span>
																				@elseif($contract['status'] == 'inProgress')
																					<span class="text-blue" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-truck"></i></span>
																				@elseif($contract['status'] == 'Outstanding')
																					<span class="text-orange" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-clock-o"></i></span>
																				@else
																					<span class="text-red" data-toggle="tooltip" title="" data-original-title="{{ $contract['status'] }}"><i style="cursor: pointer" class="fa fa-times"></i></span>
																				@endif
																			</td>
																			<td style="text-align: right; width: 30px"><i class="fa fa-plus viewcontent contracts" style="cursor: pointer;"></i></td>
																		</tr>
																	</tbody>
																</table>
																{{-- Add additionnal information for the contracts and give a specific class (tbodycontent) for toggle it --}}
																<div class="col-md-12 tbodycontent">
																	<ul class="list-unstyled">
																		<li>
																			<i class="fa fa-flag-checkered"></i> 
																			<span data-toggle="tooltip" title="" data-original-title="{{ $contract['startlocation'] }}">
																				<b>{{ str_limit($contract['startlocation'], 50, $end = '...') }}</b>
																			</span> >> 
																			<span data-toggle="tooltip" title="" data-original-title="{{ $contract['endlocation'] }}">
																				<b>{{ str_limit($contract['endlocation'], 50, $end = '...') }}</b>
																			</span>
																			<span>
																				 ({{ number_format($contract['volume'], 2, '.', ' ') }} m<sup>3</sup>)
																			</span>
																		</li>
																		@if(isset($contract['title']) && strlen($contract['title']) > 0)
																			<li>
																				<i class="fa fa-bullhorn" data-original-title=" {{ $contract['title'] }}" title="" data-toggle="tooltip"></i> 
																				Title: <b>{{ str_limit($contract['title'], 100, $end = '...') }}</b>
																			</li>
																		@endif
																		<li>
																			<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateIssued'] }}" title="" data-toggle="tooltip"></i> 
																			Issued: <b>{{ Carbon\Carbon::parse($contract['dateIssued'])->diffForHumans() }}</b>
																		</li>
																		{{-- Add a conditionnal check. If a contract is not completed we show the expiration date else nothing --}}
																		@if(!isset($contract['dateCompleted']))
																		<li>
																			<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateExpired'] }}" title="" data-toggle="tooltip"></i> 
																			Expires: <b>{{ Carbon\Carbon::parse($contract['dateExpired'])->diffForHumans() }}</b>
																		</li>	
																		@endif
																		{{-- If a contract is accepted we show the date else nothing --}}
																		@if(isset($contract['dateAccepted']))
																		<li>
																			<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateAccepted'] }}" title="" data-toggle="tooltip"></i> 
																			Accepted: <b>{{ Carbon\Carbon::parse($contract['dateAccepted'])->diffForHumans() }}</b> 
																			by <img src='{{ App\Services\Helpers\Helpers::generateEveImage($contract['acceptorID'], 32) }}' class='img-circle' style='width: 18px;height: 18px;'> 
																			<b><span rel="id-to-name">{{ $contract['acceptorID'] }}</span></b>
																		</li>
																		@endif
																		{{-- If a contract is completed we show the date else nothing --}}
																		@if(isset($contract['dateCompleted']))
																		<li>
																			<i class="fa fa-clock-o" data-original-title=" {{ $contract['dateCompleted'] }}" title="" data-toggle="tooltip"></i> 
																			Completed: <b>{{ Carbon\Carbon::parse($contract['dateCompleted'])->diffForHumans() }}</b>
																		</li>
																		@endif
																		<li>
																			<i class="fa fa-money"></i> 
																			<b>{{ number_format($contract['reward'], 2, '.', ' ') }}</b> ISK in reward
																		</li>
																		<li>
																			<i class="fa fa-money"></i> 
																			<b>{{ number_format($contract['collateral'], 2, '.', ' ') }}</b> ISK in collateral
																		</li>
																	</ul>
																</div><!-- ./col-md-12 -->
															@endforeach
														</div><!-- ./col-md-12 -->
													</div><!-- ./row -->
												</div><!-- ./box-body -->
											</div><!-- ./box -->
										</div> <!-- ./col-md-6 -->

                                	</div><!-- ./row -->
                                </div><!-- /.box-body -->
                            </div><!-- ./box -->
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->


	            {{-- character market orders --}}
	            <div class="tab-pane" id="market_orders">
	            	<div class="row">
	            		<div class="col-md-12">
	            			<div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Market Orders ({{ count($market_orders) }})</h3>                             
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
							        <table class="table table-hover table-condensed" id="datatable">
							            <thead>
								            <tr>
								                <th style="width: 10px">#</th>
								                <th>Type</th>
								                <th>Price P/U</th>
								                <th>Issued</th>
								                <th>Expires</th>
								                <th>Order By</th>
								                <th>Location</th>
								                <th>Type</th>
								                <th>Vol</th>
								                <th>Min. Vol</th>
								                <th>State</th>
								            </tr>
							            </thead>
							            <tbody>
											@foreach ($market_orders as $order)
									            <tr>
									                <td>{{ $order->orderID }}</td>
									                <td>
									                	@if ($order->bid)
										                	Buy
										                @else
										                	Sell
										                @endif
									                </td>
									                <td>
									                	@if ($order->escrow > 0)
										                	<span data-toggle="tooltip" title="" data-original-title="Escrow: {{ $order->escrow }}">
											                	<i class="fa fa-money pull-right"></i> {{ number_format($order->price, 2, '.', ' ') }}
											                </span>
											             @else
										                	{{ number_format($order->price, 2, '.', ' ') }}
											             @endif
									                </td>									                
									                <td>{{ $order->issued }}</td>
									                <td>
									                	{{ Carbon\Carbon::parse($order->issued)->addDays($order->duration)->diffForHumans() }}
									                </td>
									                <td>
														<a href="{{ action('CharacterController@getView', array('characterID' => $order->charID)) }}">
															<img src='//image.eveonline.com/Character/{{ $order->charID }}_32.jpg' class='img-circle' style='width: 18px;height: 18px;'>
														</a>
									                	<span rel="id-to-name">{{ $order->charID }}</span>
									                </td>
									                <td>{{ $order->location }}</td>
									                <td>
									                	<img src='//image.eveonline.com/Type/{{ $order->typeID }}_32.png' style='width: 18px;height: 18px;'>
									                	{{ $order->typeName }}
									                </td>
									                <td>{{ $order->volRemaining }}/{{ $order->volEntered }}</td>
									                <td>{{ $order->minVolume }}</td>
									                <td>{{ $order_states[$order->orderState] }}</td>
									            </tr>
											@endforeach

							        	</tbody>
							        </table>
                                </div><!-- /.box-body -->
                            </div>
		                </div> <!-- ./col-md-12 -->
		            </div> <!-- ./row -->
	            </div><!-- /.tab-pane -->


	        </div><!-- /.tab-content -->
	    </div><!-- nav-tabs-custom -->
	</div><!-- ./col-md-12 -->  
</div><!-- ./row -->
@stop

@section('javascript')
<script type="text/javascript">
	// First Hide all contents. Not very clean to add a fake class.. TODO: Think another way to do this
	$(".tbodycontent").hide(); 
	// on button click. Not very clean to add a fake class.. TODO: Think another way to do this
	$(".viewcontent").on("click", function( event ){ 
		// get the tag direct after the button
		if($(this).hasClass('contracts')){
			// if we are in Contracts view, we check the next Div Tag
			var contents = $(this).closest( "table").next( "div" ); 
		} else {
			// if we are in Asset view, we check the next Tbody tag
			var contents = $(this).closest( "tbody").next( "tbody" ); 
		}
		
		// Show or hide
		contents.toggle();

		// some code for stylish
		if (contents.is(":visible")){
			$(this).removeClass('fa-plus').addClass('fa-minus');
			$(this).closest("tr").css( "background-color", "#EBEBEB" ); // change the background color of container (for easy see where we are)
			contents.css( "background-color", "#EBEBEB" ); // change the background color of content (for easy see where we are)
		} else {
			$(this).removeClass('fa-minus').addClass('fa-plus'); 
			$(this).closest("tr").css( "background-color", "#FFFFFF" ); // reset the background color on container when we hide content
		}
	});
	
	// $(function () {
	$("li#journal_graph").click(function() {
		var options = { chart: {
			renderTo: 'chart',
			type: 'line',
			zoomType: 'x',
			},
			title: {
				text: 'Daily ISK Delta',
			},
			xAxis: {
				title: {
					text: 'Time'
				},
				labels: {
					enabled: false
				},
			},
			yAxis: {
				title: {
					text: 'Amount'
				},
				labels: {
					enabled: false
				},
			},
			series: [{}]
		};

		var data;
		$.getJSON("{{ action('CharacterController@getWalletDelta', array('characterID' => $character->characterID)) }}",function(json){

			var deltas = [];
			for (i in json) {
				deltas.push([json[i]['day'], parseInt(json[i]['daily_delta'])]);
			}

			options.series[0].name = "Delta";
			options.series[0].data = deltas;

			var chart = new Highcharts.Chart(options);

			// Trigger a fake resize to get the chart to calculate the width
			// correctly
			$(window).trigger('resize');
		});
	});
	$( document ).ready(function() {
		var items = [];
		var arrays = [], size = 250;

		$('[rel="id-to-name"]').each( function(){
		//add item to array
			items.push( $(this).text() );
		});

		var items = $.unique( items );

		while (items.length > 0)
			arrays.push(items.splice(0, size));

		$.each(arrays, function( index, value ) {

			$.ajax({
				type: 'POST',
				url: "{{ action('HelperController@postResolveNames') }}",
				data: {
					'ids': value.join(',')
				},
				success: function(result){
					$.each(result, function(id, name) {

						$("span:contains('" + id + "')").html(name);
					})
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(xhr);
					console.log(textStatus);
					console.log(errorThrown);
				}
			});
		});
	});
</script>
@stop
