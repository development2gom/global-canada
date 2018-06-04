<div class="site-menubar">

	<div class="site-menubar-body">

		<div>

			<div>

				<ul class="site-menu padding-20 padding-top-50">

					<!--  <li class="site-menu-category">Menu</li> -->

<li class="site-menu-item">

<?=CHtml::link ( '<i class="site-menu-icon i wb-home" aria-hidden="true"></i>

			                    <span class="site-menu-title">My contests </span>', array ("adminPanel/contests"), array ("class" => "animsition-link","id" => "test" ) );?>

		                </li>

					<li class="site-menu-item">

<?=CHtml::link ( '<i class="site-menu-icon i wb-pie-chart" aria-hidden="true"></i>

			                    <span class="site-menu-title">' . Yii::t ( 'site', 'progreso' ) . '</span>', array ("adminPanel/dashboard/".$tokenContest), array ("class" => "animsition-link","id" => "test" ) );?>

		                </li>



					<li class="site-menu-item">

			                  <?=CHtml::link ( '<i class="site-menu-icon i wb-stats-bars" aria-hidden="true"></i>

			                    <span class="site-menu-title">' . Yii::t ( 'site', 'progresoJueces' ) . '</span>', array ('adminPanel/judgeProgress/'.$tokenContest ), array ("class" => "animsition-link" ) );?>

		                </li>



					<li class="site-menu-item">

			                <?=CHtml::link ( '<i class="icon i icont md-accounts" aria-hidden="true"></i>

			                    <span class="site-menu-title">' . Yii::t ( 'site', 'competitors' ) . '</span>', array ("adminPanel/competitors/".$tokenContest ), array ("class" => "animsition-link" ) );?>

						</li>

					<li class="site-menu-item">

			                <?=CHtml::link ( '<i class="icon i iconm ion-university"></i>

			                    <span class="site-menu-title">' . Yii::t ( 'site', 'finalists' ) . '</span>', array ("adminPanel/finalists/".$tokenContest ), array ("class" => "animsition-link" ) );?>

                		</li>


					<li class="site-menu-item">

                		 <?=CHtml::link ( '<i class="icon i icont md-camera-alt" aria-hidden="true"></i>

			                    <span class="site-menu-title">' . Yii::t ( 'site', 'menciones' ) . '</span>', array ("adminPanel/mentions/".$tokenContest ), array ("class" => "animsition-link" ) );?>

			                 </li>

					<li class="site-menu-item">

							<?= CHtml::link('<i class="icon i iconms md-swap" aria-hidden="true"></i><span class="site-menu-title">'.Yii::t('site','categoryConflicts').'</span>',array('adminPanel/categoryConflicts/'.$tokenContest), array("class"=>"animsition-link")); ?>

                		</li>

				</ul>

			</div>

		</div>

	</div>

</div>