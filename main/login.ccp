<Page id="1" templateExtension="html" relativePath=".." fullRelativePath=".\main" secured="False" urlType="Relative" isIncluded="False" SSLAccess="False" isService="False" cachingEnabled="False" cachingDuration="1 minutes" wizardTheme="Silver" wizardThemeVersion="3.0" needGeneration="0">
	<Components>
		<Record id="2" sourceType="Table" urlType="Relative" secured="False" allowInsert="False" allowUpdate="False" allowDelete="False" validateData="True" preserveParameters="None" returnValueType="Number" returnValueTypeForDelete="Number" returnValueTypeForInsert="Number" returnValueTypeForUpdate="Number" name="Login" wizardOrientation="Vertical" wizardFormMethod="post" PathID="Login" returnPage="home.ccp">
			<Components>
				<Button id="3" urlType="Relative" enableValidation="True" isDefault="False" name="Button_DoLogin" parentName="Login" wizardThemeItem="FooterIMG" wizardButtonImage="ButtonLoginOn" PathID="LoginButton_DoLogin">
					<Components/>
					<Events>
						<Event name="OnClick" type="Server">
							<Actions>
								<Action actionName="Login" actionCategory="Security" id="4" redirectToPreviousPage="True" loginParameter="login" passwordParameter="password" autoLoginParameter="autoLogin"/>
							</Actions>
						</Event>
					</Events>
					<Attributes/>
					<Features/>
				</Button>
				<TextBox id="5" visible="Yes" fieldSourceType="DBColumn" dataType="Text" name="username" fieldSource="user_name" required="True" wizardSize="20" wizardMaxLength="100" wizardIsPassword="False" parentName="Login" PathID="Loginusername">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</TextBox>
				<TextBox id="6" visible="Yes" fieldSourceType="DBColumn" dataType="Text" name="password" fieldSource="user_pwd" required="True" wizardSize="20" wizardMaxLength="100" wizardIsPassword="True" parentName="Login" PathID="Loginpassword">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</TextBox>
			</Components>
			<Events/>
			<TableParameters/>
			<SPParameters/>
			<SQLParameters/>
			<JoinTables/>
			<JoinLinks/>
			<Fields/>
			<ISPParameters/>
			<ISQLParameters/>
			<IFormElements/>
			<USPParameters/>
			<USQLParameters/>
			<UConditions/>
			<UFormElements/>
			<DSPParameters/>
			<DSQLParameters/>
			<DConditions/>
			<SecurityGroups/>
			<Attributes/>
			<Features/>
			<PKFields/>
		</Record>
	</Components>
	<CodeFiles>
		<CodeFile id="Events" language="PHPTemplates" name="login_events.php" forShow="False" comment="//" codePage="windows-1252"/>
		<CodeFile id="Code" language="PHPTemplates" name="login.php" forShow="True" url="login.php" comment="//" codePage="windows-1252"/>
	</CodeFiles>
	<SecurityGroups/>
	<CachingParameters/>
	<Attributes/>
	<Features/>
	<Events>
		<Event name="OnLoad" type="Client">
			<Actions>
				<Action actionName="Set Focus" actionCategory="General" id="7" form="Login" name="login"/>
			</Actions>
		</Event>
	</Events>
</Page>
